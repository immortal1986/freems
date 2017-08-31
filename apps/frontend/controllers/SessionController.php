<?php

namespace Multiple\Frontend\Controllers;

use Forms\LoginForm;
use Auth\Exception as AuthException;

class SessionController extends BaseController {

	public function indexAction() {

	}

	public function loginAction() {
		//$form = new LoginForm();

		try {
			if (!$this->request->isPost()) {
				if ($this->auth->hasRememberMe()) {
					return $this->auth->loginWithRememberMe();
				}
			} else {

				if ($this->request->getPost() == false) {
				/*	foreach ($form->getMessages() as $message) {
						$this->flash->error($message);
					}*/
				} else {

				/*	$this->auth->check([
						'email' => $this->request->getPost('email'),
						'password' => $this->request->getPost('password'),
						'remember' => $this->request->getPost('remember')
					]);*/

				//	return $this->response->redirect('users');
				}
			}
		} catch (AuthException $e) {
			$this->flash->error($e->getMessage());
		}

	//	$this->view->form = $form;
	}

	public function registerAction() {
		$form = new SignUpForm();

		if ($this->request->isPost()) {

			if ($form->isValid($this->request->getPost()) != false) {

				$user = new Users([
					'name' => $this->request->getPost('name', 'striptags'),
					'email' => $this->request->getPost('email'),
					'password' => $this->security->hash($this->request->getPost('password')),
					'profilesId' => 2
				]);

				if ($user->save()) {
					return $this->dispatcher->forward([
						'controller' => 'index',
						'action' => 'index'
					]);
				}

				$this->flash->error($user->getMessages());
			}
		}

		$this->view->form = $form;
	}

	public function logoutAction() {
		$this->auth->remove();
		return $this->response->redirect('index');
	}

}