<?php

namespace Multiple\Backend\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends BaseController
{
    public function indexAction()
    {
        return $this->response->forward('login');
    }
}
