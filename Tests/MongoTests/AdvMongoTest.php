<?php
use \Yamw\Lib\Mongo\AdvMongo;

class AdvMongoTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $conn = AdvMongo::getConn();
    }
}
