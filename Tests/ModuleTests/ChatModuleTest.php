<?php
use \Modules\Chat\ChatController;
use \Modules\Chat\ChatBuilder;
use \Yamw\Lib\Config;

class ChatModuleTest extends PHPUnit_Framework_TestCase
{
    public $controller;

    public function setUp()
    {
        $this->controller = new ChatController('chat', 'index', 'chat');
    }

    public function testChatData()
    {
        $data = $this->controller->fetchEntries();

        self::assertTrue(is_array($data));

        foreach ($data as $entry) {
            self::assertInstanceOf('\\Yamw\\Models\\Chat', $entry);
        }
    }

    public function testFetchEntriesVariableNumbersValid()
    {
        $data = $this->controller->fetchEntries();
        self::assertLessThanOrEqual(Config::get('chat.maxentries'), count($data));

        $data = $this->controller->fetchEntries(1);
        self::assertCount(1, $data);
    }

    public function testChatModuleManualTriggeredOutput()
    {
        $data = $this->controller->fetchEntries();
        self::assertLessThanOrEqual(Config::get('chat.maxentries'), count($data));

        self::setTestExpectations();

        ChatBuilder::push($data);
    }

    public function testChatModuleAutomaticOutput()
    {
        self::setTestExpectations();

        $this->controller->indexAction();
    }

    public function setTestExpectations()
    {
        $this->expectOutputRegex('/<div id="ChatSupz">/');
        $this->expectOutputRegex('/<div id="Chat">/');
        $this->expectOutputRegex('/<div class="ChatAuthor">/');
    }
}
