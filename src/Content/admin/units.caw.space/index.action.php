<?php

use \Yamw\Lib\MySql\AdvMySql;

noTemplate();
hasToBeAdmin();

$this->units = AdvMySql::getTable('units_space')
    ->orderby('affiliation', 'DESC')
    ->orderby('name', 'ASC')
    ->execute();
forward404Unless($this->units);