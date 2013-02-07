<?php
$this->noTemplate();
hasToBeAdmin();
forward404Unless($Request->Id);

$this->update = AdvMySql::getTable('updates')
    ->where('id', $Request->Id)
    ->execute();
forward404Unless($this->update);

include 'Form.form.php';
foreach($this->update as $obj) {
    $this->form = new UpdateForm('UpdateForm', $obj->getDate(), $obj->getDescription(), $obj->getRawText(), $obj->getId());
    $this->form->enableAjax('update/update', $obj->getId());
}