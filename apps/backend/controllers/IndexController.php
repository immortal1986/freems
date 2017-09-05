<?php

namespace Multiple\Backend\Controllers;

class IndexController extends BaseController
{
    public function indexAction()
    {
        return $this->response->forward('login');
    }
}
