<?php
namespace Yamw\Lib;

use Yamw\Lib\MySql\AdvMySql;

class Meta
{
    private static $instance;
    private $data = array();
    private $table = 'meta';

    final public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->data = AdvMySql::getTable($this->table)->execute();

        $new_data = array();
        $this->assign();
    }

    private function assign()
    {
        foreach ($this->data as &$value) {
            $this->$value['name'] =& $value;
        }
    }

    public function get($name)
    {
        return $this->$name;
    }

    public function set($name, $content)
    {
        global $config;
        if (isset($this->data[0]['name'])) {
            // no table delimeters
            $this->data[$name] = mb_convert_encoding($content, "UTF-8");
        }
        $this->Assign();
        return $this;
    }

    public function save()
    {
        foreach ($this->data as &$value) {
            $value = mb_convert_encoding($value, "UTF-8");
        }
        $new_data = array();
        foreach ($this->data as $key => &$value) {
            if (strpos($key, DB_TB_DELIMITER)) {
                $te = explode(DB_TB_DELIMITER, $key);
                $new_data[$te[1]] = $value;
            } else {
                $new_data[$key] = $value;
            }
        }

        // TODO: Save the data to MySql
        // Don't forget that existing entries may be overwritten
    }
}
