<?php

use \Yamw\Lib\MySql\AdvMySql;

$request = $this->getRequest();

noTemplate();
hasToBeAdmin();
forward404Unless($request->valueExists('id'));

$this->unit = AdvMySql::getTable('units_space')->where('id', $request->getValue('id'))->limit(1)->execute();
forward404Unless($this->unit);

include 'Form.form.php';
foreach($this->unit as $obj) {
    $this->form = new UnitForm('EditForm', $obj->getName(), $obj->getAffiliationId(), $obj->getLayerId(), $obj->getRawDesc());
    $this->form->enableAjax('admin/'.$module.'/update', $obj->getId());
}
