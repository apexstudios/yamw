<?php
use Yamw\Lib\MySql\AdvMySql;

$this->about = AdvMySql::getTable('staff')->where('draft', '0')->orderby(id, ASC)->execute();
forward404Unless($this->about);
