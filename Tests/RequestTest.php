<?php
use Yamw\Lib\Request;

class RequestTest extends PHPUnit_Framework_TestCase
{
    public $className = '\Yamw\Lib\Request';

    public function testInitializationPopulatesInitialState()
    {
        // Just to be sure that it is non-existent
        self::assertSame(null, Request::get('heyho'));
        self::assertSame(null, Request::get('dzde'));

        $data = array(
            'dzde' => 'default',
            'heyho' => 'it exists'
        );
        Request::init($data);

        // Now the data exists
        self::assertSame('it exists', Request::get('heyho'));
        self::assertSame('default', Request::get('dzde'));

        // Init with other data
        $data = array(
            'dzde' => 'wut?',
            'heyho' => 'yup, it\'s true'
        );
        Request::init($data);

        // Now some other data exists
        self::assertSame('yup, it\'s true', Request::get('heyho'));
        self::assertSame('wut?', Request::get('dzde'));
    }

    public static function testCanSetRequestValues()
    {
        // Init with other data
        $data = array(
            'section' => 'wut?',
        );
        Request::init($data);

        self::assertSame('wut?', Request::get('section'));

        // Set it to a new value
        Request::set('section', 'whatever');
        self::assertSame('whatever', Request::get('section'));
    }

    public function testCanPopulateFromGet()
    {
        // Just filling the superglobal...
        $_GET['hi'] = 'hey';
        self::assertArrayHasKey('hi', $_GET);

        Request::populateFromGet();

        self::assertEquals('hey', Request::get('get-hi'));
    }

    public function testCanPopulateFromPost()
    {
        // Just filling the superglobal...
        $_POST['hi'] = 'hey';
        self::assertArrayHasKey('hi', $_POST);

        Request::populateFromPost();

        self::assertEquals('hey', Request::get('post-hi'));
    }
}
