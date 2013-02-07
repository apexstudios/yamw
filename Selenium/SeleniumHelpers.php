<?php
namespace Selenium;

class SeleniumHelpers
{
    public static function logIn($t)
    {
        // Click on the login link
        $link = $t->byLinkText('login');
        $link->click();

        // Check that we are on the login page
        $t->assertRegExp('/user\/login\/index\/[0-9a-zA-Z=]{0,}$/', $t->url());

        // Login
        $userField = $t->byId('RegForm_name');
        $pwField = $t->byId('RegForm_pw');
        $form = $t->byId('RegForm');

        $userField->value('Anh Nhan');
        $pwField->value('hello');

        $form->submit();

        sleep(2);

        $response = $t->byId('response');
        $t->assertContains('You have been logged in successfully!', $response->text());

        // Wait a little bit
        sleep(4);
    }

    public static function isNotLoggedIn($t)
    {
        // Assert that we are not logged in yet
        $greeting = $t->byId('usersys');
        $t->assertRegExp('/Hello, Guest/', $greeting->text());
    }
}
