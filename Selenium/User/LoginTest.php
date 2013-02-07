<?php
namespace Selenium\User;

use \Selenium\SeleniumHelpers;

class LoginTest extends \Tests_Selenium2TestCase_BaseTestCase
{
    /**
     * @dataProvider data_login_urls
     */
    public function testCanLogin($url)
    {
        $this->url($url);
        self::assertStringEndsWith($url, $this->url());

        SeleniumHelpers::isNotLoggedIn($this);

        SeleniumHelpers::logIn($this);

        // Assert that we have been redirected back
        // Cut that, we're skipping that
        // self::assertStringEndsWith($url, $this->url());

        // Assert that we are logged in yet
        $greeting = $this->byId('usersys');
        self::assertRegExp('/Hello, Anh Nhan/', $greeting->text());
    }

    public function data_login_urls()
    {
        return array(
            array('/'),
            array('/caw'),
            array('/gallery')
        );
    }
}
