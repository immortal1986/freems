<?php

use Phalcon\Config;
use Phalcon\Logger;

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new Config([
	'database' => [
		'adapter' => 'Mysql',
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'dbname' => 'storage_database_sql',
		'charset' => 'utf8',
		'option' => array()
	],

	'application' => [
		'appDir' => APP_PATH . '/',
		'controllersDir' => APP_PATH . '/controllers/',
		'modelsDir' => APP_PATH . '/models/',
		'migrationsDir' => APP_PATH . '/migrations/',
		'viewsDir' => APP_PATH . '/views/',
		'pluginsDir' => APP_PATH . '/plugins/',
		'libraryDir' => APP_PATH . '/library/',
		'helpersDir' => APP_PATH . '/library/',
		'cacheDir' => BASE_PATH . '/cache/',
		'baseUri' => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
		'formsDir' => APP_PATH . '/forms/',
		'publicUrl' => 'database',
		'cryptSalt' => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D'
	],
	'mail' => [
		'fromName' => 'Immortal',
		'fromEmail' => 'sysprag@gmail.com',
		'smtp' => [
			'server' => 'smtp.gmail.com',
			'port' => 587,
			'security' => 'tls',
			'username' => 'ok495939',
			'password' => '3990550kYzkA'
		]
	],
	'amazon' => [
		'AWSAccessKeyId' => '',
		'AWSSecretKey' => ''
	],
	'logger' => [
		'path' => BASE_PATH . '/logs/',
		'format' => '%date% [%type%] %message%',
		'date' => 'D j H:i:s',
		'logLevel' => Logger::DEBUG,
		'filename' => 'application.log',
	],
	'useMail' => false /*   ON/OFF  */
]);