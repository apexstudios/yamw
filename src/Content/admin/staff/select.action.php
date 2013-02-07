<?php
noTemplate();
hasToBeAdmin();

$this->members = AdvMySql::getTable('staff')->orderby('id')->execute();
forward404Unless($this->members);
