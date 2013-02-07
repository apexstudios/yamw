<?php
use Yamw\Models\Chat;

class ChatTest extends PHPUnit_Framework_TestCase
{
    public $chat;
    public $fields = array('Id', 'Uid', 'Name', 'Time', 'Text', 'RawText', 'draft');

    public function setUp()
    {
        $this->chat = Yamw\Lib\MySql\AdvMySql::getTable('chat')->limit(5)->execute();
    }

    public function tearDown()
    {
        unset($this->chat);
    }

    public function testFields()
    {
        foreach ($this->chat as $entry) {
            // Selected the right model?
            self::assertInstanceOf('\Yamw\Models\Chat', $entry);

            foreach ($this->fields as $field) {
                // self::assertInstanceOf('\Yamw\Lib\Property', $entry->$field, "$field no valid YamwProperty!");
                // self::assertEmpty($entry->$field, "$field is empty!");
                self::assertTrue(isset($entry->$field));
            }
        }
    }

    public function testChangeFields()
    {
        foreach ($this->chat as $entry) {
            foreach ($this->fields as $field) {
                $prev = $entry->$field;
                $next = $this->rand_string();
                $entry->$field = $next;

                self::assertNotEquals($prev, $entry->$field);
                self::assertEquals($next, $entry->$field);
            }
        }
    }

    private function rand_string()
    {
        $length = 30;

        $tmp = array_merge(range('a', 'z'), range('A', 'Z'));

        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $rand = rand(0, 51);
            $string .= $tmp[$rand];
        }

        return $string;
    }
}
