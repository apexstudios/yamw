<?php
namespace Modules\User;

class UserBuilder
{
    private static $render_loginform = false;
    
    public static function renderLoginForm($enabled = true)
    {
        if (!is_bool($enabled)) {
            throw new \InvalidArgumentException('$enabled is not a bool!');
        }
        
        static::$render_loginform = $enabled;
    }
    
    public static function logoutMessage()
    {
        set_slot('title', 'Logging you out');
        println('You have been signed out successfully! You will be redirected to the homepage now');
        header('Refresh: 5;'.getAbsPath());
        println('<a href="'.getAbsPath().'">Click here if it takes longer than five seconds to load for you</a>');
    }
}
