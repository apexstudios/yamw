<?php
noTemplate();
hasToBeAdmin();

include 'Form.form.php';
$this->form = new StaffForm('StaffForm');
$this->form->enableAjax('about/insert');
