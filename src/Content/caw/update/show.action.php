<?php
use Yamw\Lib\Request;
use Yamw\Lib\MySql\AdvMySql;

forward404Unless(Request::exists('id'));

$this->update = AdvMySql::getTable('updates')->where('id', Request::get('id'))->execute();
forward404Unless($this->update);