<?php
use Yamw\Lib\MySql\AdvMySql;

/**
 *
 * @author AnhNhan <anhnhan@outlook.com>
 */
class AdvMySqlTest extends PHPUnit_Framework_TestCase
{
    public function testStubs()
    {
        \Yamw\Lib\MySql\AdvMySql_Conn::getConn(false);

        /*AdvMySql::createTable('');
        AdvMySql::deleteTable('');
        AdvMySql::dropTable('');*/
        AdvMySql::insertTable('')
        ->insertData('s', 's', 'text')
        ->insertData('s', 9, 'integer')
        ->insertFunction('se', 'vs', '', 'text')
        ->insertFunction('se', 'vs', '', 'integer')
        ->setModel('g')->generateQuery();

        $update = AdvMySql::updateTable('')
        ->updateData('gs', 'rs')
        ->updateFunction('ges', 'Bdr', 'raw');

        self::assertInstanceOf('\Yamw\Lib\MySql\AdvMySql_updateTable', $update);
        $update->generateQuery();
    }

    /**
     * @dataProvider data_query
     */
    public function testQueryGeneration($expquery, $query)
    {
        self::assertRegExp($expquery, trim($query));
    }

    public function data_query()
    {
        return array(
            array(
                "/SELECT.*?
FROM test/i",
                AdvMySql::getTable('test')->generateQuery()->getQuery()
            ),
            array(
                "/SELECT.*?hi.*?
FROM test.*?
ORDER BY aa DESC/i",
                AdvMySql::getTable('test')->select('hi')->orderby('aa', 'DESC')->generateQuery()->getQuery()
            ),
            array(
                "/SELECT.*?
FROM test.*?NATURAL JOIN joint/i",
                AdvMySql::getTable('test')->naturalJoin('joint')->generateQuery()->getQuery()
            ),
        );
    }

    /**
     * @outputBuffering enabled
     */
    public function testCanNotCallConstructor()
    {
        self::expectOutputRegex('/Warning/');
        new AdvMySql();
    }
}
