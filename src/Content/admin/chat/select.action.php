<?php
use Yamw\Lib\MySql\AdvMySql;

noTemplate();
hasToBeAdmin();

$this->chat = AdvMySql::getTable('chat')->orderby('id')->limit(20)->execute();
forward404Unless($this->chat);
