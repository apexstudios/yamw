<?php
use Yamw\Lib\Request;
use Yamw\Lib\UAM\UAM;

Request::populateFromPost(array('name', 'pw', 'prev_site', 'ajax' => false));

$name = Request::get('post-name');
$pw = Request::get('post-pw');
$prev_site = Request::get('post-prev_site') ? Request::get('post-prev_site') :
    getAbsPath();
$ajax = Request::get('post-ajax');

if ($name) {
    $this->result = UAM::getInstance()->logIn($name, $pw);

    if ($ajax || $this->result == 'success') {
        $this->showForm = false;
    } else {
        $this->showForm = true;
    }
} else {
    $this->showForm = true;
    $this->result = '';
}
