<?php
noTemplate();
hasToBeAdmin();
forward404Unless(Request::exists('id'));

$this->unit = AdvMySql::getTable('units_space')->where('id', Request::get('id'))->limit(1)->execute();
forward404Unless($this->unit);

include 'Form.form.php';
foreach($this->unit as $obj) {
    $this->form = new UnitForm('EditForm', $obj->getName(), $obj->getAffiliationId(), $obj->getLayerId(), $obj->getRawDesc());
    $this->form->enableAjax('admin/'.$module.'/update', $obj->getId());
}
