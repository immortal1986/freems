<?php
use Phalcon\Mvc\User\Component;

namespace Flash;

class CustomFlash extends \Phalcon\Flash\Session
{
    public function message($type, $message)
    {
        $message = '<a href="#" class="close" data-dismiss="alert">&times;</a><strong></strong>' . $message;
        parent::message($type, $message);
    }
}