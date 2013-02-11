<?php
namespace Yamw\Lib\MySql;

use Yamw\Lib\Config;
use Yamw\Lib\Schemer\Schemer;

/**
 * <p>This class is responsible for all MySql-Stuff (primitive...)
 * <b>Warning! Singleton!</b></p>
 * @author Anh Nhan
 * @package Yamw
 * @subpackage MySql
 */
class MySql
{
    private static $instance;
    private $dbn;
    private $pref;

    private $commonQuery;

    /**
     * Just the connection...
     * @var \mysqli
     */
    private $conn;

    /**
     * Etablishes the connection
     * @return none
     */
    private function __construct()
    {
        global $Config;
        $this->dbn = Config::get('mysql.dbname');
        $this->pref = Config::get('mysql.table_prefix');

        $this->conn = AdvMySql_Conn::getConn();

        $this->commonQuery = "SELECT * FROM `{$this->dbn}`.`::TABLE::` ORDER BY id DESC";
    }

    /**
     * @return MySql
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    /**
     * Querys for a table and returns data in an improvised ORM-Format
     * @param table name $name
     * @return Array of Model objects
     */
    public function getTable($name, $model = 'None')
    {
        $name = Schemer::getTableName($name);
        return $this->query2Array($this->query("SELECT * FROM `{$this->dbn}`.`{$name}` ORDER BY id DESC"), $model);
    }

    public function findTable($name, $search, $limit = -1, $model = 'None')
    {
        if (!is_array($search)) {
            return false;
        }

        $name = Schemer::getTableName($name);
        $sql = "SELECT * FROM `{$this->dbn}`.`{$name}` WHERE";

        foreach ($search as $key => $value) {
            $sql .= " `$key` = '$value' AND";
        }

        $sql .= ';; ORDER BY id DESC';
        $sql = str_replace(' AND;;', '', $sql);

        if ($limit != -1) {
            $sql .= " LIMIT 0, {$limit}";
        }
        $r = $this->query($sql);
        return $this->query2Array($r, $model);
    }

    /**
     *
     * @param mysqli_result $result
     * @param string $model
     * @return multitype:|multitype:unknown multitype:string
     */
    private function query2Array($result, $model)
    {
        if ($result->num_rows === 0) {
            return array();
        }

        $a = array();
        $model = $this->model == 'None' ? 'None' : '\\Yamw\\Models\\'.$this->model;

        while ($r_all = $result->fetch_assoc()) {
            $t = array();
            foreach ($r_all as $key => $value) {
                $t[$key] = mb_convert_encoding($value, 'UTF-8');
            }

            if ($model != 'None') {
                $a[] = new $model($t);
            } else {
                $a[] = $t;
            }
        }

        return $a;
    }

    public function checkIfExists($name, $search = array())
    {
        $name = Schemer::getTableName($name);


        $sql = "SELECT id FROM `{$this->dbn}`.`{$name}` WHERE";

        foreach ($search as $key => $value) {
            $key = escape($key);
            $value = escape($value);

            $sql .= " `$key` = '$value' AND";
        }

        $sql .= ';;';
        $sql = str_replace(' AND;;', '', $sql);

        $r = $this->query($sql);
        return mysqli_num_rows($r);
    }

    /**
     * Querys for a table and only getting results which ain't draft
     * @param $name The name of the table you want to have
     * @param [$extras] Extra search parameters
     * @param [$limit] Limit the given return to that number
     * @param [$model] The Model class to be used
     */
    public function getPublicTable($name, $extras = array(), $limit = -1, $model = 'None')
    {
        return $this->findTable($name, array_merge(array('draft' => 0), $extras), $limit, $model);
    }

    public function findSingleRow($name, $extras = array(), $model = 'Model')
    {
        return $this->findTable($name, $extras, 1, $model);
    }

    public function getCount($table, $extras = array('id' => "' OR 1 --"))
    {
        $table = Schemer::getTableName($table);
        $sql = "SELECT id FROM `{$this->dbn}`.`{$table}` WHERE";


        foreach ($extras as $key => $value) {
            $sql .= " `$key` = '$value' AND";
        }

        $sql .= ';;';
        $sql = str_replace(' AND;;', '', $sql);

        $r = $this->query($sql);
        return mysqli_num_rows($r);
    }

    /**
     * Inserts data in a mysql tabe
     * @param string $table The name of the table
     * @param assoc_array $data Te data to be put in
     * @return none
     */
    public function insertData($table, $data)
    {
        $table = Schemer::getTableName($table);
        $sql = "INSERT INTO `".$table."` (";
        foreach ($data as $key => $value) { // Insert attributes
            $key = escape($key);
            $sql .= '`'.$key.'`, ';
        }

        $sql .= ') VALUES (';

        foreach ($data as $key => $value) { // Insert values
            $value = escape($value);
            $sql .= '\''.$value.'\', ';
        }

        $sql .= ')';
        $sql = str_replace(', )', ')', $sql);

        return $this->query($sql);
    }

    /**
     * Updates data in a mysql tabe
     * @param string $table The name of the table
     * @param assoc_array $data Te data to be updated
     * @return none
     */
    public function updateData($table, $data, $where)
    {
        $table = Schemer::getTableName($table);
        $sql = "UPDATE `".$table."` SET ";

        foreach ($data as $key => $value) {
            $key = $key;
            $value = escape($value);
            $sql .= ", `{$key}` = '{$value}'";
        }

        $sql = str_replace('SET ,', 'SET', $sql);

        $where = (strpos($where, '=')) ? $where: 'id='.(int)$where;
        $sql .= ' WHERE '.$where;

        return $this->query($sql);
    }

    public function deleteData($table, $where, $alt = false)
    {
        $table = Schemer::getTableName($table);
        if ($alt) {
            return $this->query("DELETE FROM `{$this->dbn}`.`$table` WHERE `$table`.$where LIMIT 1");
        } else {
            return $this->query("DELETE FROM `{$this->dbn}`.`$table` WHERE `$table`.`id` = $where LIMIT 1");
        }
    }

    /**
     * Makes a query and returns the result
     * @param query-string $query
     * @return MySql-Result
     */
    private function query($query)
    {
        $profiler = Profiler::getInstance();

        $profilerId = $profiler->sqlProfiler($this->query);

        $t = $this->conn->query($query);
        if ($this->conn->error) {
            throw new \Yamw\Lib\Exceptions\MySqlException($this->conn->error, $query);
        }

        if (@get_class($t) == '\mysqli_result') {
            $t->free_result();
        }

        global $num_queries;
        $num_queries++;

        $profiler->stopProfiler($profilerId);

        return $t;
    }
}
