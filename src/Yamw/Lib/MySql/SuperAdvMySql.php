<?php
namespace Yamw\Lib\MySql;

use Yamw\Lib\Config;
use YamwLibs\Infrastructure\Profiler\Profiler;

abstract class SuperAdvMySql
{
    protected $dbn;
    protected $pref;
    protected static $conn;
    protected $query;
    protected $model;
    protected $atable;
    protected $table;
    protected $result;

    public function __construct($name)
    {
        $this->pref = Config::get('mysql.table_prefix');

        if (!isset(self::$conn)) {
            self::$conn = AdvMySql_Conn::getConn();
        }

        $this->model = resolveTable2Model($name);
        $this->atable = $name;
        $this->table = (tableIsInScheme($name)) ? $this->pref.$name : $name;
        $this->table = escape($this->table);
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Execute the Query and return the result fetched into an array
     * (if defined in './config/scheme.xml' a Model will be used)
     *
     * @throws MySqlException upon error
     *
     * @return array <p>An array of the fetched result
     * Optionally this will be a class if defined properly in './config/scheme.xml'</p>
     */
    public function execute()
    {
        $this->generateQuery();

        $profiler = Profiler::getInstance();

        $profilerId = $profiler->sqlProfiler($this->query);

        $result = self::$conn->query($this->query);

        if (preg_match('/AdvMySql_getTable$/', get_class($this))) {
            $ret = $this->query2Array($result);

            @$result->free_result();
            unset($result);

            global $num_queries;
            $num_queries++;
        }

        $profiler->stopProfiler($profilerId);

        if (self::$conn->error) {
            throw new \Yamw\Lib\Exceptions\MySqlException(self::$conn->error, $this->query);
        }

        unset($this->query);

        if (preg_match('/AdvMySql_getTable$/', get_class($this))) {
            return $ret;
        } else {
            return $result;
        }
    }

    public function getQuery()
    {
        return $this->query;
    }

    /**
     *
     * @param mysqli_result $result
     * @return multitype:|multitype:unknown string
     */
    protected function query2Array($result)
    {
        if ($result->num_rows === 0) {
            return array();
        }

        $a = array();
        $model = $this->model == 'None' ? 'None' : '\\Yamw\\Models\\'.$this->model;

        while ($r_all = $result->fetch_assoc()) {
            foreach ($r_all as $key => &$value) {
                $r_all[$key] = mb_convert_encoding($value, 'UTF-8');
            }
            if ($model != 'None' || !$model) {
                $a[] = new $model($r_all);
            } else {
                $a[] = $r_all;
            }
        }

        return $a;
    }

    abstract public function generateQuery();
}
