<?php
use \Yamw\Lib\Modelizer\Modelizer;

class ModelizerTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        self::assertInstanceOf('\Yamw\Lib\Modelizer\Modelizer', Modelizer::get('somestring'));
        self::assertInstanceOf('\Yamw\Lib\Modelizer\Modelizer', Modelizer::getSingle('somestring'));

        $modelizer = Modelizer::get('sometable');
        self::assertInstanceOf('\Yamw\Lib\Modelizer\Modelizer', $modelizer::get('somestring'));

        self::assertInstanceOf('\Yamw\Lib\Modelizer\Modelizer', $modelizer->fieldEquals('somestring', 'someotherstring'));

        self::assertInstanceOf('\Yamw\Lib\Modelizer\Modelizer', $modelizer->fieldSearch('somestring', 'searchstring'));
    }

    /**
     * @expectedException Yamw\Lib\Exceptions\MissingModelException
     */
    public function testCantUseInvalidModel()
    {
        Modelizer::get('sometable')->model('somemodel');
    }

    public function htestCanQuerySomething()
    {
        $obj = Modelizer::get('hcaw_chat')->limit(1)->model('Chat')->exec();
        self::assertInstanceOf('Yamw\\Models\\Chat', $obj[1]);
    }
}
