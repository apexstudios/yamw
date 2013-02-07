<?php
$this->noTemplate();
hasToBeAdmin();

include 'Form.form.php';
$this->form = new UpdateForm('AddForm');
$this->form->enableAjax('update/insert');