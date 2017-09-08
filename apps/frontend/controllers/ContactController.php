<?php

namespace Multiple\Frontend\Controllers;

use Multiple\Frontend\Forms\LoginForm;
use Multiple\Frontend\Forms\SignUpForm;
use Multiple\Frontend\Models\Users;
use Multiple\Frontend\Forms\ContactForm;
use Multiple\Frontend\Auth\Exception as AuthException;

class ContactController extends BaseController {
	public function initialize() {
		$this->tag->setTitle('Contact us');
		parent::initialize();
	}

	public function indexAction() {
		$this->view->form = new ContactForm;
	}

	/**
	 * Saves the contact information in the database
	 */
	public function sendAction() {
		if ($this->request->isPost() != true) {
			return $this->dispatcher->forward(
				[
					"controller" => "contact",
					"action" => "index",
				]
			);
		}

		//$form    = new ContactForm;
		//$contact = new Contact();

		// Validate the form
		$data = $this->request->getPost();
		/*if (!$form->isValid($data, $contact)) {
			foreach ($form->getMessages() as $message) {
				$this->flash->error($message);
			}

			return $this->dispatcher->forward(
				[
					"controller" => "contact",
					"action" => "index",
				]
			);
		}*/

		if ($contact->save() == false) {
			foreach ($contact->getMessages() as $message) {
				$this->flash->error($message);
			}

			return $this->dispatcher->forward(
				[
					"controller" => "contact",
					"action" => "index",
				]
			);
		}

		$this->flash->success('Thanks, we will contact you in the next few hours');

		return $this->dispatcher->forward(
			[
				"controller" => "index",
				"action" => "index",
			]
		);
	}
}
