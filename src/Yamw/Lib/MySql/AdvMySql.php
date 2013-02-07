<?php
namespace Yamw\Lib\MySql;

/**
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage MySql
 */
class AdvMySql
{
    public function __construct()
    {
        print_c(
            'Warning! Class AdvMySql is *NOT* for instancing!
                Use the double scope operator!',
            COLOR_ERROR
        );
    }

    /**
     * Retrieves data from a table
     *
     * @param string $name
     *
     * @return \Yamw\Lib\MySql\AdvMySql_getTable
     */
    public static function getTable($name)
    {
        return new AdvMySql_getTable($name);
    }

    /**
     * Updates data in a table
     *
     * @param string $name
     *
     * @return \Yamw\Lib\MySql\AdvMySql_updateTable
     */
    public static function updateTable($name)
    {
        return new AdvMySql_updateTable($name);
    }

    /**
     * Inserts data into a table
     *
     * @param string $name
     *
     * @return \Yamw\Lib\MySql\AdvMySql_insertTable
     */
    public static function insertTable($name)
    {
        return new AdvMySql_insertTable($name);
    }

    /**
     * Creates a new table
     *
     * @param string $name
     *
     * @return \Yamw\Lib\MySql\AdvMySql_createTable
     */
    public static function createTable($name)
    {
        return new AdvMySql_createTable($name);
    }

    /**
     * Drops a table
     *
     * @param string $name
     *
     * @return \Yamw\Lib\MySql\AdvMySql_dropTable
     */
    public static function dropTable($name)
    {
        return new AdvMySql_dropTable($name);
    }
    /*
    public static function deleteTable($name)
    {
        return new AdvMySql_deleteTable($name);
    }
    */
}
