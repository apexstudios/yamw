<?php
use Yamw\Lib\Request;
use Yamw\Lib\UAM\UAM;

$request = $this->request;

$request->populateFromPost(array('name', 'pw', 'prev_site', 'ajax' => false));

$name = $request->getValue('post-name');
$pw = $request->getValue('post-pw');
$prev_site = $request->getValue('post-prev_site') ? $request->getValue('post-prev_site') :
    getAbsPath();
$ajax = $request->getValue('post-ajax');

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
