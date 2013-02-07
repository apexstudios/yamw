<?php
// This file is from MyBB (inc/functions_user.php)
// I have prepended the mybb-prefix to the function names
// It's possible that we won't use it

use Yamw\Lib\MySql\AdvMySql;
use Yamw\Lib\MySql\MySql;
use Yamw\Lib\UAM\UAM;

/**
 * Checks if a user with uid $uid exists in the database.
 *
 * @param int The uid to check for.
 * @return boolean True when exists, false when not.
 */
function mybb_user_exists($uid)
{
    global $db;

    $query = $db->simple_select("users", "COUNT(*) as user", "uid='".intval($uid)."'", array('limit' => 1));
    if($db->fetch_field($query, 'user') == 1)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * Checks if $username already exists in the database.
 *
 * @param string The username for check for.
 * @return boolean True when exists, false when not.
 */
function mybb_username_exists($username)
{
    global $db;
    $query = $db->simple_select("users", "COUNT(*) as user", "username='".$db->escape_string($username)."'", array('limit' => 1));
    if($db->fetch_field($query, 'user') == 1)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * Checks a password with a supplied uid.
 *
 * @param int The user id.
 * @param string The plain-text password.
 * @param string An optional user data array.
 * @return boolean|array False when not valid, user data array when valid.
 */
function mybb_validate_password_from_uid($uid, $password, $user = array())
{
    if(UAM::getInstance()->getCurUserId() == $uid)
    {
        $user = UAM::getInstance()->getCurUser();
    }
    if(!$user['password'])
    {
        $user = AdvMySql::getTable('mybb_users')->select('uid,username,password,salt,loginkey,usergroup')->where('uid',intval($uid))->limit(1)->execute();
        $user = $user[0];
    }
    if(!$user['salt'])
    {
        // Generate a salt for this user and assume the password stored in db is a plain md5 password
        $user['salt'] = mybb_generate_salt();
        $user['password'] = mybb_salt_password($user['password'], $user['salt']);
        $sql_array = array(
            "salt" => $user['salt'],
            "password" => $user['password']
        );
        MySql::getInstance()->updateData('mybb_users', $sql_array, "uid='".$user['uid']."'");
    }

    if(!$user['loginkey'])
    {
        $user['loginkey'] = mybb_generate_loginkey();
        $sql_array = array(
            "loginkey" => $user['loginkey']
        );
        MySql::getInstance()->updateData('mybb_users', $sql_array, "uid='".$user['uid']."'");
    }
    if(mybb_salt_password(md5($password), $user['salt']) == $user['password'])
    {
        return $user;
    }
    else
    {
        return false;
    }
}

/**
 * Updates a user's password.
 *
 * @param int The user's id.
 * @param string The md5()'ed password.
 * @param string (Optional) The salt of the user.
 * @return array The new password.
 */
function mybb_update_password($uid, $password, $salt="")
{
    global $MySql;

    $newpassword = array();

    // If no salt was specified, check in database first, if still doesn't exist, create one
    if(!$salt)
    {
        $query = AdvMySql::getTable("mybb_users")->select("salt")->where("uid", $uid)->limit(1)->execute();
        $user = $query[0];
        if($user['salt'])
        {
            $salt = $user['salt'];
        }
        else
        {
            $salt = mybb_generate_salt();
        }
        $newpassword['salt'] = $salt;
    }

    // Create new password based on salt
    $saltedpw = mybb_salt_password($password, $salt);

    // Generate new login key
    $loginkey = mybb_generate_loginkey();

    // Update password and login key in database
    $newpassword['password'] = $saltedpw;
    $newpassword['loginkey'] = $loginkey;
    MySql::getInstance()->updateData('mybb_users', $newpassword, "uid='$uid'");

    return $newpassword;
}

/**
 * Salts a password based on a supplied salt.
 *
 * @param string The md5()'ed password.
 * @param string The salt.
 * @return string The password hash.
 */
function mybb_salt_password($password, $salt)
{
    return md5(md5($salt).$password);
}

/**
 * Generates a random salt
 *
 * @return string The salt.
 */
function mybb_generate_salt()
{
    return random_str(8);
}

/**
 * Generates a 50 character random login key.
 *
 * @return string The login key.
 */
function mybb_generate_loginkey()
{
    return random_str(50);
}

function random_str($length="8")
{
    $set = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");
    $str='';
    for($i = 1; $i <= $length; $i++)
    {
        $ch = rand(0, count($set)-1);
        $str .= $set[$ch];
    }
    return $str;
}

/**
 * Updates a user's salt in the database (does not update a password).
 *
 * @param int The uid of the user to update.
 * @return string The new salt.
 */
function mybb_update_salt($uid)
{
    global $MySql;

    $salt = mybb_generate_salt();
    $sql_array = array(
        "salt" => $salt
    );
    #$db->update_query("users", $sql_array, "uid='{$uid}'", 1);
    MySql::getInstance()->updateData('mybb_users', $sql_array, "uid='$uid'");
    

    return $salt;
}

/**
 * Generates a new login key for a user.
 *
 * @param int The uid of the user to update.
 * @return string The new login key.
 */
function mybb_update_loginkey($uid)
{
    global $MySql;

    $loginkey = mybb_generate_loginkey();
    $sql_array = array(
        "loginkey" => $loginkey
    );
    #$db->update_query("users", $sql_array, "uid='{$uid}'", 1);
    MySql::getInstance()->updateData('mybb_users', $sql_array, "uid='$uid'");

    return $loginkey;

}