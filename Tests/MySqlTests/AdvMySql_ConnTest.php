<?php
use \Yamw\Lib\Config;
use \Yamw\Lib\MySql\AdvMySql_Conn;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 */
class AdvMySql_ConnTest extends PHPUnit_Framework_TestCase
{
    public function testValidConnection()
    {
        \Yamw\Lib\MySql\AdvMySql_Conn::getConn(false);

        self::assertInstanceOf('\mysqli', AdvMySql_Conn::getConn());
    }
}
