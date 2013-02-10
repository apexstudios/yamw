<?php
namespace Yamw\Lib\Auth;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Auth
 */
class AuthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Yamw\Lib\Exceptions\AuthenticationException
     * @expectedExceptionCode 200
     */
    public function testAuthRequired()
    {
        Auth::required(Auth::LVL_STAFF);
    }

    public function testAuthSuccessful()
    {
        $orig = Auth::currentAuthLevel();

        Auth::updateAuthLevel(Auth::LVL_ADMIN);

        try {
            Auth::required(Auth::LVL_STAFF);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        Auth::updateAuthLevel($orig);
    }
}
