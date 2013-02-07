<?php
namespace Selenium\Chat;

use Yamw\Lib\Schemer\Schemer;

use \Selenium\SeleniumHelpers;
use \Yamw\Lib\MySql\AdvMySql_Conn;

/**
 *
 * @author AnhNhan <anhnhan@outlook.com>
 *
 */
class ChatTest extends \Tests_Selenium2TestCase_BaseTestCase
{
    public function tearDown()
    {
        Schemer::resetTable('chat');
    }

    public function testChatHasAtLeastTwoEntries()
    {
        $this->url('/');

        // Just making sure that we are on the right website
        self::assertStringEndsWith('/', $this->url());
        $link = $this->byCssSelector('h1');
        self::assertEquals('This is the HMH Home (Index action)', $link->text());

        $chat_entries = $this->byCssSelector('#Chat')->elements($this->using('css selector')->value('div'));
        self::assertGreaterThanOrEqual(2, count($chat_entries));
    }

    public function testChatCanBeSentAsGuest()
    {
        $this->sendChat();
    }

    public function testChatCanBeSentAsUser()
    {
        $this->sendChat(true);
    }

    public function sendChat($login = null)
    {
        $this->url('/');

        // Assert that we are not logged in yet
        $greeting = $this->byId('usersys');
        self::assertRegExp('/Hello, Guest/', $greeting->text());

        if ($login) {
            SeleniumHelpers::logIn($this);
        }

        $flap = $this->byClassName('flapLabel');
        $loc_orig = $flap->location();
        $x_orig = $loc_orig['x'];

        // First activate
        $this->moveto($flap);
        $flap->click();
        sleep(2);

        // Check that the flap moved
        $flap = $this->byClassName('flapLabel');
        $loc_new = $flap->location();
        $x_new = $loc_new['x'];

        self::assertGreaterThan($x_orig, $x_new);

        // Now send the chat message
        $textfield = $this->byId('ChatText');

        // self::assertEquals('Message', $textfield->text());

        $textfield->click();

        self::assertEquals('', $textfield->text());

        $msg = 'TestMessage';
        $textfield->value($msg);

        self::assertEquals($msg, $textfield->value());

        $this->byId('chatForm')->submit();
        sleep(2);

        $this->refresh();
        $chat_entries = $this->byCssSelector('#Chat')->elements($this->using('css selector')->value('.ChatEntry'));

        $cond = false;
        foreach ($chat_entries as $entry) {
            $text = $entry->elements($this->using('css selector')->value('.ChatText'));
            $time = $entry->elements($this->using('css selector')->value('.ChatTime'));
            $author = $entry->elements($this->using('css selector')->value('.ChatAuthor'));

            if (
                $text[0]->text() == $msg &&
                $time[0]->text() == "Just right now" &&
                $author[0]->text() == $login ? "Anh Nhan" : "Guest"
            ) {
                $cond = true;
                break;
            }
        }

        self::assertTrue($cond, "We did not find the chat entry we just posted!");
    }
}
