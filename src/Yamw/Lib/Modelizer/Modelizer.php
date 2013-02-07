<?php
/*
 *
 */
namespace Yamw\Lib\Modelizer;

use \Yamw\Lib\Exceptions\MissingModelException;
use \Yamw\Lib\Assertions\BasicAssertions;

/**
 * Consider this an interface to the various db and model layers
 *
 * @author AnhNhan
 * @package Yamw
 * @subpackage Modelizer
 */
class Modelizer
{
    /**
     *
     * @param string $tablename
     *
     * @return Modelizer
     */
    public static function get($tablename)
    {
        if (!is_string($tablename)) {
            throw new \InvalidArgumentException('Table name is not a string!');
        }

        $classname = __CLASS__;
        return new $classname($tablename);
    }

    /**
     *
     * @param string $tablename
     *
     * @return Modelizer
     */
    public static function getSingle($tablename)
    {
        return self::get($tablename)->limit(1)->single();
    }

    private $table;

    /**
     * A list of options used for this query.
     *
     * Possible options:
     * <ul>
     * <li>limit
     *  <ul>
     *   <li>length
     *   <li>start
     *  </ul>
     * <li>single
     *
     * @var list<string>
     */
    private $options = array();

    private $query = array();

    /**
     * The array holding all the objects resulting from the query
     *
     * @var list<mixed>
     */
    private $result;

    private function __construct($tablename)
    {
        $this->table = $tablename;
    }

    public function limit($length, $start = 0)
    {
        $this->options['limit'] = array('length' => $length, 'start' => $start);
        return $this;
    }

    private function single($enabled = true)
    {
        $this->options['single'] = $enabled;
        return $this;
    }

    public function fieldEquals($fieldname, $content)
    {
        $this->query[] = array(
            'field' => $fieldname,
            'content' => $content,
            'type' => 'equal'
        );
        return $this;
    }

    public function fieldSearch($fieldname, $search)
    {
        $this->query[] = array(
            'field' => $fieldname,
            'content' => $search,
            'type' => 'search'
        );
        return $this;
    }

    public function model($name)
    {
        BasicAssertions::assertIsString($name);

        $name = "\\Yamw\\Models\\$name";
        $this->modelExists($name);

        $this->options['model'] = $name;

        return $this;
    }

    public function exec()
    {
        // TODO: Execute the query
        $result = array();
        $this->query();
        $this->query2Array();
        $result = $this->result;

        if ($this->getOption('single') && count($result)) {
            return $result[0];
        } else {
            return $result;
        }
    }

    private function getOption($name)
    {
        BasicAssertions::assertIsString($name);

        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    private function modelExists($name)
    {
        if (!class_exists($name)) {
            throw new MissingModelException("Model $name does not exist!");
        }
    }

    private function query2Array()
    {
        $model = $this->getOption('model');
        if (!count($this->result)) {
            return;
        }

        $result = array();

        foreach ($this->result as $value) {
            if ($model === null || $model === 'None') {
                $result[] = $value;
            } else {
                $result[] = new $model($value);
            }

        }

        $this->result = $result;
    }

    private function query()
    {
        $model = $this->getOption('model');
        $dbtype = 'mysql';

        if ($model !== null && $model !== 'None') {
            $dbtype = $model::$dbtype;
        }

        if ($dbtype == 'mongodb') {
            $this->queryMongoDb();
        } else {
            $this->queryMySql();
        }

        $this->query2Array();
    }

    private function queryMySql()
    {
        // TODO: Something
        $sql = \Yamw\Lib\MySql\AdvMySql::getTable($this->table)->setModel('None');

        foreach ($this->query as $query) {
            // TODO: Construct query
            switch ($query['type']) {
                case 'equal':
                    $sql->where($query['field'], $query['content'], '=');
                    break;
            }
        }

        foreach ($this->options as $key => $value) {
            // TODO: Construct options
            switch ($key) {
                case 'limit':
                    $sql->limit($value['start'], $value['start'] + $value['length']);
                    break;
                default:
                    break;
            }
        }

        $this->result = $sql->execute();
    }

    private function queryMongoDb()
    {
        // TODO: Something
    }
}
