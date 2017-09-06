<?php

namespace Multiple\Frontend\Controllers;

use Multiple\Frontend\Forms\LoginForm;
use Multiple\Frontend\Forms\SignUpForm;
use Multiple\Frontend\Auth\Exception as AuthException;
use Multiple\Frontend\Models\Users;

class SessionController extends BaseController {

	public function indexAction() {
		$this->tag->setTitle('About us');
		parent::initialize();

	}

	private function _registerSession($user) {
		$this->session->set(
			'auth',
			array(
				'id' => $user->id,
				'email' => $user->email,
			)
		);
	}

	public function loginAction() {
		$form = new LoginForm();
		try {
			if ($this->request->isPost()) {

				if ($this->request->getPost() == false) {
					/*	foreach ($form->getMessages() as $message) {
							$this->flash->error($message);
						}*/
				} else {
					$email    = $this->request->getPost('email');
					$password = $this->request->getPost('password');


					$user = Users::findFirst(
						array(
							"email = :email:  AND password = :password: AND activity=1 ",
							'bind' => array(
								'email' => $email,
								'password' => sha1($password)
							)
						)
					);
					echo '<pre>';
					print_r($user);
					echo '</pre>';
					die(__LINE__);
					//$this->view->disable();

					if ($user) {
						$this->_registerSession($user);

						return $this->dispatcher->forward(
							array(
								'controller' => 'login',
								'action' => 'index'
							)
						);
					}
				}
			}
		} catch (AuthException $e) {
			$this->flash->error($e->getMessage());
		}

		//	$this->view->form = $form;
	}

	public function registerAction() {
		$form = new SignUpForm();


		$user = new Users();

		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');

		$user->username = $username;
		$user->password = $this->security->hash($password);

		$user->save();

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