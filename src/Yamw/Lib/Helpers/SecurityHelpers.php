<?php
use Yamw\Lib\UAM\UAM;
use Yamw\Lib\Exceptions\AuthenticationException;

/**
 * Checks if the current user is an admin. Automatically blocks the user
 * if he is not an admin.
 */
function hasToBeAdmin()
{
    if (!UAM::getInstance()->getCurUserIsAdmin()) {
        throw new AuthenticationException("Security breach detected. Access has been
            restricted in accordance to UNSC Security Act [Section III Paragraph 433].
The violence of the given paragraph has been logged and will be prosecuted with
            all applicable laws.", 'admin');
    }
}

/**
 * Checks if the current user is logged in. Automatically blocks the user
 * if he is not logged in.
 */
function hasToBeLoggedIn()
{
    if (!UAM::getInstance()->isLoggedIn()) {
        throw new AuthenticationException("You are apparently not logged in!", 'user');
    }
}
