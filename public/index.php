<?php

error_reporting(E_ALL);

use Phalcon\Loader;
use Phalcon\Mvc\Router;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Application as BaseApplication;

class Application extends BaseApplication {

	protected function registerServices() {

		$di = new FactoryDefault();

		$loader = new Loader();

		$loader
			->registerDirs([__DIR__ . '/../apps/library/'])
			->register();

		// Registering a router
		$di->set('router', function () {

			$router = new Router();

			$router->setDefaultModule("frontend");

			$router->add('/:controller/:action', [
				'module' => 'frontend',
				'controller' => 1,
				'action' => 2,
			])->setName('frontend');

			$router->add("/login", [
				'module' => 'backend',
				'controller' => 'login',
				'action' => 'index',
			])->setName('backend-login');

			$router->add("/admin/products/:action", [
				'module' => 'backend',
				'controller' => 'products',
				'action' => 1,
			])->setName('backend-product');

			$router->add("/products/:action", [
				'module' => 'frontend',
				'controller' => 'products',
				'action' => 1,
			])->setName('frontend-product');

			return $router;
		});



		$this->setDI($di);
	}

	public function main() {

		$this->registerServices();

		// Register the installed modules
		$this->registerModules([
			'frontend' => [
				'className' => 'Multiple\Frontend\Module',
				'path' => '../apps/frontend/Module.php'
			],
			'backend' => [
				'className' => 'Multiple\Backend\Module',
				'path' => '../apps/backend/Module.php'
			]
		]);

		echo $this->handle()->getContent();
	}
}

$application = new Application();
$application->main();
