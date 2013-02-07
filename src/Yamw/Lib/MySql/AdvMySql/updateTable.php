<?php
namespace Yamw\Lib\MySql;

class AdvMySql_updateTable extends SuperAdvMySql
{
    protected $data = array();

    public function updateData($column, $data, $data_type = 'raw')
    {
        $this->data[$column] = array(
            'data' => $data,
            'data_type' => $data_type,
            'type' => 'data'
        );

        return $this;
    }

    public function updateFunction($column, $function, $data, $data_type = 'raw')
    {
        $this->data[$column] = array(
            'data' => $data,
            'data_type' => $data_type,
            'type' => 'function',
            'function' => $function
        );

        return $this;
    }

    public function generateQuery()
    {
        // Stub
    }
}
