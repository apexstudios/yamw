<?php
$this->noTemplate();
hasToBeAdmin();

$this->updates = AdvMySql::getTable('updates')->orderby('id', 'DESC')->execute();
forward404Unless($this->updates);