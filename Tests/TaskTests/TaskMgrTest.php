<?php
use Yamw\Lib\Tasks;

/**
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @covers \Yamw\Lib\Tasks
 */
class TaskMgrTest extends PHPUnit_Framework_TestCase
{
    public function testAddTask()
    {
        self::markTestIncomplete('Tasks::addTask has not yet been implemented.');
        // Yes, this is a stub
        $task = Tasks::addTask('hey', array('this' => 'is an option'));
        self::assertInstanceOf('\MongoCollection', $task);
    }

    /**
     * @dataProvider data_invalid_names
     * @expectedException InvalidArgumentException
     */
    public function testAddTaskInvalidArgument($name)
    {
        Tasks::addTask($name, array());
    }

    public function data_invalid_names()
    {
        return array(
            array(''),
            array(array()),
            array($this),
            array(3.1456)
        );
    }

    public function testRemoveTaskWithArrayReturnsArray()
    {
        $result = Tasks::removeTask('somestring');

        $array = array(
            'somestring',
            'hey',
            __CLASS__
        );

        $res = Tasks::removeTask($array);
        $this->assertCount(count($array), $res);

        foreach ($array as $task) {
            self::assertArrayHasKey($task, $res);
        }
    }

    public function testProcessTask()
    {
        self::markTestIncomplete('Tasks::processTask has not yet been implemented.');
    }

    public function testMarkTask()
    {
        self::markTestIncomplete('Tasks::markTask has not yet been implemented.');
    }
}
