<?php
namespace Yamw\Lib\MySql;

class AdvMySql_insertTable extends SuperAdvMySql
{
    private $data = array();

    public function insertData($column, $data, $data_type = 'raw')
    {
        $this->data[$column] = array('data' => $data, 'data_type' => $data_type, 'type' => 'data');
        return $this;
    }

    public function insertFunction($column, $function, $data = '', $data_type = 'raw')
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
        $this->query = 'INSERT INTO '.$this->table.' (';

        foreach ($this->data as $column_name => $column) {
            $this->query .= ''.$column_name.', ';
        }
        $this->query .= '::) VALUES (';
        foreach ($this->data as $column_name => $column) {
            switch ($column['type']) {
                default:
                case 'data':
                    switch ($column['data_type']) {
                        default:
                        case 'int':
                        case 'integer':
                        case 'float':
                        case 'raw':
                            $this->query .= escape($column['data']).', ';
                            break;
                        case 'string':
                        case 'char':
                        case 'varchar':
                        case 'text':
                            $this->query .= '\''.escape($column['data']).'\', ';
                            break;
                    }
                    break;
                case 'function':
                    $column['data'] = escape($column['function']).'('.escape($column['data']).')';
                    switch($column['data_type']) {
                        default:
                        case 'int':
                        case 'integer':
                        case 'float':
                        case 'raw':
                            $this->query .= escape($column['data']).', ';
                            break;
                        case 'string':
                        case 'char':
                        case 'varchar':
                        case 'text':
                            $this->query .= '\''.escape($column['data']).'\', ';
                            break;
                    }
                    break;
            }
        }
        $this->query .= '::)';

        $this->query = str_replace(', ::', '', $this->query);
    }
}
