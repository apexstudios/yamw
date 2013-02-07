<?php
use \Yamw\Lib\Config;
use \Yamw\Lib\MySql\AdvMySql_Conn;

/**
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @covers \Yamw\Lib\MySql\AdvMySql_Conn
 */
class AdvMySql_ConnTest extends PHPUnit_Framework_TestCase
{
    public function testValidConnection()
    {
        self::assertInstanceOf('\mysqli', AdvMySql_Conn::getConn());
    }

    /**
     * Currently does not work out how I want it
     */
    public function htestInvalidConnection()
    {
        // Back up the config setting
        $conf = Config::get('mysql.host');
        // Overwrite it with some crap
        Config::set('mysql.host', 'lalalala');

        try {
            $this->setExpectedException('\Yamw\Lib\Exceptions\MySqlException');
            AdvMySql_Conn::getConn(true);
        } catch (\Yamw\Lib\Exceptions\MySqlException $e) {
            echo $e->getMessage();
        }

        // Restore the config setting
        $conf = Config::set('mysql.host', $conf);
    }
}
