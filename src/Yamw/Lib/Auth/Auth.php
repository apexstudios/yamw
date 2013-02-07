<?php
namespace Yamw\Lib\Auth;

use \Yamw\Lib\Assertions\BasicAssertions;

class Auth
{
    const LVL_ALL =     50;
    const LVL_USER =    100;
    const LVL_TEAM =    150;
    const LVL_STAFF =   200;

    // Team members
    const LVL_APEX =    0x01;
    const LVL_LHAOLE =  0x02;
    const LVL_BLS =     0x04;

    const LVL_ADMIN =   400;

    private static $authLevel = self::LVL_ALL;

    /**
     * Checks whether the current auth level suffices or a higher auth level
     * is required.
     *
     * @param number $lvl
     * @throws \Yamw\Lib\Exceptions\AuthenticationException
     */
    public static function required($lvl = self::LVL_ALL)
    {
        BasicAssertions::assertIsInt($lvl);

        if (static::currentAuthLevel() < $lvl) {
            throw new \Yamw\Lib\Exceptions\AuthenticationException(
                "Failing authentication!",
                $lvl
            );
        }
    }

    /**
     * Returns the current auth level
     *
     * @return number
     */
    public static function currentAuthLevel()
    {
        return static::$authLevel;
    }

    /**
     * Updates the current auth level
     *
     * @param number $lvl
     */
    public static function updateAuthLevel($lvl = self::LVL_ALL)
    {
        BasicAssertions::assertIsInt($lvl);
        static::$authLevel = $lvl;
    }
}
