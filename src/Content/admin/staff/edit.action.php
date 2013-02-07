<?php
use Yamw\Lib\Request;

noTemplate();
hasToBeAdmin();
forward404Unless(Request::exists('id'));

$this->member = AdvMySql::getTable('staff')->where('id', Request::get('id'))->limit(1)->execute();

include 'Form.form.php';
foreach($this->member as $obj) {
    $this->form = new StaffForm('StaffForm', $obj->getName(), $obj->getPosition(), $obj->getImage(false), $obj->getRawDesc());
    $this->form->enableAjax('about/update', $obj->getId());
}
