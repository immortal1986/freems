<?php

namespace Multiple\Frontend\Controllers;

class IndexController extends BaseController {
	public function initialize() {
		parent::initialize();
		$this->tag->setTitle('Welcome');
	}

	public function indexAction() {
		/*$password = $this->security->hash('111');
		echo '<pre>';
		print_r($password);
		echo '</pre>';
		die(__LINE__);*/
		if (!$this->request->isPost()) {
			$this->direct->notice('This is a sample application of the Phalcon Framework.
                Please don\'t provide us any personal information. Thanks');
		}
	}
}
