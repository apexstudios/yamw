<?php
use Yamw\Lib\MySql\AdvMySql;

$this->meta_list = AdvMySql::getTable('meta')
    ->setModel('MMeta')
    ->orderby('name', ASC)
    ->execute();
