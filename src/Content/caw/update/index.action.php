<?php
use Yamw\Lib\MySql\AdvMySql;

$this->updates = AdvMySql::getTable('updates')
    ->where('draft', '0')
    ->orderby('id', 'DESC')
        ->select('id')
        ->select('draft')
        ->select('date')
        ->select('desc')
    ->execute();
forward404Unless($this->updates);
