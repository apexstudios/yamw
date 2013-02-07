<?php
namespace Yamw\Lib\MySql;

class AdvMySql_dropTable extends SuperAdvMySql
{
    public function generateQuery()
    {
        $this->query = "DROP TABLE {$this->table}";
    }
}
