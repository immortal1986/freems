<?php

namespace Multiple\Frontend\Controllers;

use Phalcon\Mvc\Controller;

abstract class BaseController extends Controller{
	protected function initialize()
	{
		$this->tag->prependTitle('INVO | ');
		$this->view->setTemplateAfter('main');
	}
}