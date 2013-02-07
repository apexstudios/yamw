<?php
namespace Yamw\Lib\Modelizer;

use \Yamw\Lib\MySql\AdvMySql;
use \Yamw\Lib\MySql\MySql;
use \Yamw\Lib\Mongo\AdvMongo;
use \Yamw\Lib\Property;

abstract class Modelizer_Base
{
    protected static $table;
    public static $dbtype = 'mysql';
    protected static $id_column = 'id';
    protected $data = array();
    protected static $array_map = array();

    protected $persists = false;

    public function __construct(array $_data)
    {
        global $Config;

        $t = array();

        foreach ($_data as $key => $value) {
            if (@get_class($val)) {
                // Is already a class, don't bother with it
                $t[$key] = $value;
            } else {
                $class = $this->dataType($key);
                $t[$key] = new $class($value);
            }
        }

        $this->setData($t);
    }

    final public function __get($name)
    {
        if (array_key_exists($name, static::$array_map)) {
            return $this->data[static::$array_map[$name]];
        } else {
            return $this->data[$name];
        }
    }

    final public function __set($name, $val)
    {
        $t = $this->dataType($name);
        if (array_key_exists($name, static::$array_map)) {
            return $this->data[static::$array_map[$name]] = new $t($val);
        } else {
            return $this->data[$name] = new $t($val);
        }
    }

    final public function __isset($name)
    {
        if (array_key_exists($name, static::$array_map)) {
            return true;
        } elseif (isset($this->data[$name])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the datatype used for the column/key
     *
     * @param string $key
     * @return string
     */
    protected function dataType($key)
    {
        return '\\Yamw\\Lib\\Property';
    }

    final public function getData()
    {
        return $this->data;
    }

    final public function setData(array $val)
    {
        $this->data = $val;
    }

    final public function getTable()
    {
        return static::$table;
    }

    final public function getId()
    {
        return $this->data[static::$id_column];
    }

    final public function setId($_id)
    {
        $this->data[static::$id_column] = $_id;
    }

    final public function getIdColumn()
    {
        return static::$id_column;
    }

    /**
     * Whether a column is in an external table/document or not
     * @param string $key the name/key of the column
     * @return bool|string
     */
    private function isExternal($key)
    {
        return array();
    }

    public static function retrieve($var)
    {
        switch (static::$dbtype) {
            case 'mysql':
                $t = AdvMySql::getTable(static::$table)
                    ->where(static::$id_column, $var)->limit(1)->execute();
                break;
            case 'mongodb':
            default:
                throw new \Exception('Unsupported DB Type');
                break;
        }

        if (count($t) && @get_class($t[0])) {
            return $t[0];
        } elseif (count($t) && is_array($t[0])) {
            $class = __CLASS__;
            return new $class($t[0]);
        } else {
            // Should we instead throw a Exception here?
            return false;
        }
    }

    public function update()
    {
        // TODO: something?
    }

    /**
     * Tells the entry to persist after the application has run
     */
    final public function persist()
    {
        $this->persists = true;
        // return $this->save();
    }

    /**
     * Tells the entry to not persist after PHP has finished
     */
    final public function dontPersist()
    {
        $this->persists = false;
    }

    /**
     * Alias for Modelizer_Base::dontPersist()
     * @see Modelizer_Base::dontPersist()
     */
    final public function transient()
    {
        $this->dontPersist();
    }

    /**
     * Whether the entry is supposed to persist after PHP has run
     *
     * @return boolean
     */
    final public function isPersisting()
    {
        return $this->persists;
    }

    public function remove()
    {
        switch(static::$dbtype) {
            case 'mysql':
                return MySql::getInstance()
                ->deleteData(
                    $this->getTable(),
                    $this->getIdColumn()."='".$this->getId()."'"
                );
                break;
            case 'mongodb':
                return AdvMongo::getConn()
                ->selectCollection($this->getTable())
                ->remove(
                    array($this->getIdColumn() => $this->getId()),
                    array('w' => 1)
                );
                break;
            default:
                throw new \Exception('Unsupported DB Type');
                break;
        }
    }

    /**
     * Saves the current entry immediately to the database
     * @throws \Exception
     */
    public function save()
    {
        // Prepare the data here
        // Check

        // DB Logic
        switch (static::$dbtype) {
            case 'mysql':
                // First we try search for it in the Database
                $check = AdvMySql::getTable($this->getTable())
                ->select('count(*)')
                ->where($this->getIdColumn(), $this->getId())->execute();
                // Then we act accordingly
                if (count($check)) {
                    // Already exists
                } else {
                    // Insert new one
                }
                break;
            case 'mongodb':
                // Same for MongoDB
                break;
            default:
                throw new \Exception('Unsupported DB Type');
                break;
        }

        // Aftermath?
    }

    public function __destruct()
    {
        if ($this->isPersisting()) {
            $this->save();
        }
    }
}
