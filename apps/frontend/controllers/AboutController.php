<?php
namespace Multiple\Frontend\Controllers;

class AboutController extends BaseController
{
    public function initialize()
    {
        $this->tag->setTitle('About us');
        parent::initialize();
    }

    public function indexAction()
    {
    }
}
