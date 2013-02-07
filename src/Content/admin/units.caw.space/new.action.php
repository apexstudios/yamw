<?php
noTemplate();
hasToBeAdmin();

include 'Form.form.php';
$this->form = new UnitForm('AddForm');
$this->form->enableAjax('admin/'.$module.'/insert');
