<?php

namespace Multiple\Frontend;

use Phalcon\Loader;
use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

use Exception;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Flash\Direct as DirectSession;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\Url;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;

use Multiple\Frontend\Elements\Elements;

class Module implements ModuleDefinitionInterface {

	public function registerAutoloaders(DiInterface $di = null) {
		$loader = new Loader();

		$loader->registerNamespaces(
			[
				'Multiple\Frontend\Controllers' => '../apps/frontend/controllers/',
				'Multiple\Frontend\Models' => '../apps/frontend/models/',
				'Multiple\Frontend\Forms' => '../apps/frontend/forms/',
				'Multiple\Frontend\Library' => '../apps/frontend/library/',
				'Multiple\library\Plugins' => '../apps/frontend/plugins/',
				//  'Multiple\library\helpers' => '../apps/library/helpers/',
			]
		);

		$loader->register();
	}

	public function registerServices(DiInterface $di) {
		/*$di->set('config', function () {
			$config = new ConfigIni("config/config.ini");
			return $config->api->toArray();
		});*/
		//$config = require __DIR__ . '/config/config.php';
		//$di->setShared('config', ['host'=>'localhost']);

		$di->set('url', function () {
			$url = new Url();
			$url->setBaseUri('/freems/');
			return $url;
		}
		);

		$di->set('dispatcher', function () {
			$dispatcher    = new MvcDispatcher();
			$eventsManager = new EventsManager();
			// Attach a event listener to the dispatcher (if any)
			// For example:
			// $eventManager->attach('dispatch', new \My\Awesome\Acl('frontend'));
			//$eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);
			//	$eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);


			$eventsManager->attach(
				"dispatch:beforeException",
				function (Event $event, $dispatcher, Exception $exception) {
					// Handle 404 exceptions
					if ($exception instanceof DispatchException) {
						$dispatcher->forward(
							[
								"controller" => "index",
								"action" => "show404",
							]
						);

						return false;
					}

					// Alternative way, controller or action doesn't exist
					switch ($exception->getCode()) {
						case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
						case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
							$dispatcher->forward(
								[
									"controller" => "index",
									"action" => "show404",
								]
							);

							return false;
					}
				}
			);

			$dispatcher->setEventsManager($eventsManager);
			$dispatcher->setDefaultNamespace('Multiple\Frontend\Controllers\\');
			return $dispatcher;
		});


		$di->set('view', function () {
			$view = new View();
			$view->setViewsDir('../apps/frontend/views/');
			return $view;
		});

		//$config = $di->getShared('config');
		$config = [];
		$di->set("db", function () use ($config) {
			$eventsManager = new EventsManager();
			$logger        = new FileLogger("../apps/logs/debug.log");
// Слушаем все события базы данных
			$eventsManager->attach(
				"db:beforeQuery",
				function ($event, $connection) use ($logger) {
					$logger->log(
						$connection->getSQLStatement(),
						Logger::INFO
					);
				}
			);
			$connection = new Mysql(
				[
					'host' => $config->database->host,
					'username' => $config->database->username,
					'password' => $config->database->password,
					'dbname' => $config->database->dbname,
					'schema' => $config->database->schema,
					'options' => [PDO::ATTR_CASE => PDO::CASE_LOWER,
					              PDO::ATTR_PERSISTENT => TRUE,
					              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
					]
				]
			);
// Назначаем EventsManager экземпляру адаптера базы данных
			$connection->setEventsManager($eventsManager);
			return $connection;
		}
		);

		$di->set('session', function () {
			$session = new SessionAdapter();
			$session->start();
			return $session;
		});

		$di->set('elements', function () {
			return new Elements();
		});

		$di->set('customflash', function () {
			return new CustomFlash();
		});
		
		$di->set('flash', function () {
			return new FlashSession(array(
				'error' => 'alert alert-danger',
				'success' => 'alert alert-success',
				'notice' => 'alert alert-info',
				'warning' => 'alert alert-warning'
			));
		});

		$di->set('direct', function () {
			return new DirectSession(array(
				'error' => 'alert alert-danger',
				'success' => 'alert alert-success',
				'notice' => 'alert alert-info',
				'warning' => 'alert alert-warning'
			));
		});

	}
}
