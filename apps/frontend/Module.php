<?php

namespace Multiple\Frontend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Flash\Direct as DirectSession;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\Url;

use Phalcon\Mvc\Model\Metadata\Memory as MetaData;

use Multiple\Frontend\Elements\Elements;


class Module implements ModuleDefinitionInterface {
	/**
	 * Registers the module auto-loader
	 *
	 * @param DiInterface $di
	 */
	public function registerAutoloaders(DiInterface $di = null) {
		$loader = new Loader();

		$loader->registerNamespaces(
			[
				'Multiple\Frontend\Controllers' => '../apps/frontend/controllers/',
				'Multiple\Frontend\Models' => '../apps/frontend/models/',
				'Multiple\Frontend\Forms' => '../apps/frontend/forms/',
				'Multiple\Frontend\Library' => '../apps/frontend/library/',
				// 'Multiple\library\plugins' => '../apps/library/plugins/',
				//  'Multiple\library\helpers' => '../apps/library/helpers/',
			]
		);

		$loader->register();
	}

	/**
	 * Registers services related to the module
	 *
	 * @param DiInterface $di
	 */
	public function registerServices(DiInterface $di) {
		$di->set(
			'url',
			function () {
				$url = new Url();

				$url->setBaseUri('/freems/');

				return $url;
			}
		);

		// Registering a dispatcher
		$di->set('dispatcher', function () {
			$dispatcher = new Dispatcher();
			// $eventsManager = new EventsManager;
			$eventManager = new Manager();
			// Attach a event listener to the dispatcher (if any)
			// For example:
			// $eventManager->attach('dispatch', new \My\Awesome\Acl('frontend'));
			/*   $eventManager->attach('dispatch:beforeDispatch', new SecurityPlugin);
			   $eventManager->attach('dispatch:beforeException', new NotFoundPlugin);*/

			$dispatcher->setEventsManager($eventManager);
			$dispatcher->setDefaultNamespace('Multiple\Frontend\Controllers\\');
			return $dispatcher;
		});

		// Registering the view component
		$di->set('view', function () {
			$view = new View();
			$view->setViewsDir('../apps/frontend/views/');
			return $view;
		});

		$di->set('db', function () {
			return new Mysql(
				[
					"host" => "localhost",
					"username" => "root",
					"password" => "",
					"dbname" => "freems"
				]
			);
		});

		$di->set('session', function () {
			$session = new SessionAdapter();
			$session->start();
			return $session;
		});

		$di->set('elements', function () {
			return new Elements();
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
