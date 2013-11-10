<?php
namespace Yamw\Lib\MySql;

use \Yamw\Lib\Config;
use \Yamw\Lib\Exceptions\MySqlException;

final class AdvMySql_Conn
{
    private static $conn;
    private static $inst;

    private function __construct($failHardWhenFailing = true)
    {
        global $Config;
        try {
            self::$conn = new \mysqli(
                Config::get('mysql.host'),
                Config::get('mysql.username'),
                Config::get('mysql.password'),
                Config::get('mysql.dbname')
            );
            if (self::$conn->connect_errno) {
                echo "Error with MConn! ".self::$conn->connect_errno;
                throw new MySqlException(self::$conn->connect_errno, 'NoQuery(tm)');
            }
        } catch (MySqlException $e) {
            if ($failHardWhenFailing) {
                ob_end_clean();
                $e->getMessage();
                exit(1);
            }
        }

        self::$conn->set_charset("utf8");
    }

    /**
     * @return \mysqli
     */
    public static function getConn($failHardWhenFailing = true)
    {
        if (!isset(self::$inst)) {
            $class = __CLASS__;
            self::$inst = new $class($failHardWhenFailing);
        }
        return self::$conn;
    }
}
