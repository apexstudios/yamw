<?php
namespace Selenium\User;

class AuthorizationTest extends \Tests_Selenium2TestCase_BaseTestCase
{
    public static $unauthorized_sites = array(
        '/admin',
        '/dba'
    );
    
    public function testGetsUnauthorizedMessagesWhenNotLoggedIn()
    {
        foreach (static::$unauthorized_sites as $site) {
            $this->url($site);
            self::assertStringEndsWith($site, $this->url());
            
            // Assert that we are not yet logged in
            $greeting = $this->byId('usersys');
            self::assertRegExp('/Hello, Guest/', $greeting->text());
            
            $body = $this->byClassName('body');
            self::assertRegExp('/Security breach detected\./', $body->text());
            self::assertEquals('Access Restricted', $this->title());
        }
    }
}
