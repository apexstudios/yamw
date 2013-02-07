<?php
namespace Yamw\Lib\Mongo;

use Yamw\Lib\Config;

class AdvMongo
{
    protected static $conn;

    /**
     * @return \MongoDB
     * @throws Exception
     */
    public static function getConn()
    {
        if (!isset(self::$conn)) {
            try {
                $host = Config::get('mongo.host');
                self::$conn = class_exists('MongoClient') ?
                    @new \MongoClient("mongodb://{$host}/") :
                    @new \Mongo("mongodb://{$host}/");
                if (!self::$conn) {
                    throw new Exception('could not establish Mongo Connection. Missing PHP Driver?');
                }
                $dbn = Config::get('mongo.dbname');
                self::$conn =& self::$conn->$dbn;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        global $num_queries;
        $num_queries++;
        return self::$conn;
    }

    private function __construct()
    {
        // Do nothing
    }

    /**
     * @return array
     * @param array $ar The MapReduce command
     */
    public static function mapReduce($ar)
    {
        getLogger()->addNotice(
            'MapReduce command running!',
            array('src' => $ar['mapreduce'], 'dst' => $ar['out'])
        );

        return self::getConn()->command($ar);
    }

    /**
     * @return array
     * @param unknown_type $col
     * @param unknown_type $key
     * @param unknown_type $init
     * @param unknown_type $reduce
     */
    public static function group($col, $key, $init, $reduce)
    {
        return self::getConn()->$col->group($key, $init, $reduce);
    }

    /**
     * @return MongoGridFS
     * @param string $col
     */
    public static function gridFs($col = 'fs')
    {
        return self::getConn()->getGridFs($col);
    }

    public static function index($col, $arr, $opt = array())
    {
        return self::getConn()->$col->ensureIndex($arr, $opt);
    }
}
