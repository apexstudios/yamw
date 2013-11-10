<?php
class MySqlTest extends PHPUnit_Framework_TestCase
{
    public function htestStub()
    {
        self::assertTrue(true);
    }
}

class MySql extends Yamw\Lib\MySql\MySql
{
    protected function Query($query)
    {
        $query = trim($query);

        if (stripos('select') === 0) {
            // We have a select statement
        } else {
            // update, delete, insert etc.
            return true;
        }
    }
}
