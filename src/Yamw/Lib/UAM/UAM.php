<?php
namespace Yamw\Lib\UAM;

use Yamw\Lib\MySql\AdvMySql;
use Yamw\Lib\MySql\MySql;

/**
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage UAM
 */
final class UAM
{
    private static $instance;

    private $datastore;
    private $groupmgr;
    private $usermgr;

    /**
     * Contains the user id of the current user
     * @var int
     */
    private $curuser = 0;

    /**
     * @return UAM
     */
    final public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    private function __construct()
    {
        global $Config;

        $this->datastore = UAMStorage::getInstance();

        // Check in

        // First, check if the user has a cookie used for authentication
        // Then load the user for the user id found in the cookie into the storage
        if (!isset($_COOKIE['mybb_mybbuser'])) {
            return false;
        }

        $userid = explode('_', $_COOKIE['mybb_mybbuser']);
        $loginkey = $userid[1];
        $userid = (int)$userid[0];

        if ($this->users()->getLoginKeyById($userid) != $loginkey) {
            return false;
        }

        // If no user had been found, this means that his login is invalid
        // Don't let him get in!
        if (!$this->users()->getUserById($userid)) {
            return false;
        }

        if (isset($_COOKIE['mybb_sid'])) {
            $this->curuser = $this->users()->checkForSessId($_COOKIE['mybb_sid']);
        }
    }

    /**
     * Returns the UserManagement Object associated with this instance
     * @return UAMStorage
     */
    public function users()
    {
        return $this->datastore;
    }

    /**
     * Checks whether the current user is logged in, or is a guest
     * @return boolean
     */
    public function isLoggedIn()
    {
        return checkForTrue($this->curuser);
    }

    /**
     * Returns the user object for the current user
     * @return User
     */
    public function getCurUser()
    {
        return $this->users()->getUserById($this->curuser);
    }

    /**
     * Returns the user id for the current user
     * @return int
     */
    public function getCurUserId()
    {
        return $this->curuser ? $this->curuser : 0;
    }

    /**
     * Returns the username for the current user
     * @return string
     */
    public function getCurUserName()
    {
        return ($this->curuser) ? $this->users()->getUserNameById($this->curuser) : 'Guest';
    }

    /**
     * Returns the path to the avatar for the current user
     * @return <string, NULL> <p>Returns a string if an avatar has been found, or NULL</p>
     */
    public function getCurUserAvatar()
    {
        return ($this->curuser) ? $this->users()->getUserAvatarById($this->curuser) : null;
    }

    /**
     * Prints a hyperlink to the profile of the current user
     */
    public function getCurUserLink()
    {
        ob_start();
        $this->linkToUser($this->curuser);
        return ob_get_clean();
    }

    public function getCurUserSessId($cookie = true)
    {
        if ($cookie) {
            return $_COOKIE[USER_COOKIE];
        } else {
            return $this->users()->getUserSessIdById($this->curuser);
        }
    }

    public function getCurUserIsAdmin()
    {
        return ($this->curuser) ? $this->users()->isAdminById($this->curuser) : false;
    }

    public function linkToUser($id)
    {
        echo '<a href="{FORUM}user-'.$id.'.html"><b>'.$this->users()->getUserNameById((int)$id).'</b></a>';
    }

    public function generateSessId()
    {
        return md5(microtime(true));
    }

    public function logIn($name, $pw)
    {
        if (!$name || !$pw) {
            return 'Empty Input!';
        }
        $t = \Yamw\Models\MybbUser::retrieve($name);

        if ($t instanceof \Yamw\Models\MybbUser && $t->validateCredentials($pw)) {
            $this->curuser = $t->Uid;
            if (!strlen($t->SessId)) {
                $t->SessId = $this->generateSessId();

                $r = MySql::getInstance()->updateData(
                    'mybb_sessions',
                    array('sid' => $t->SessId, 'useragent' => $_SERVER['HTTP_USER_AGENT']),
                    'uid='.$t->Uid
                );

                if (!mysqli_affected_rows(\Yamw\Lib\MySql\AdvMySql_Conn::getConn())) {
                    MySql::getInstance()->deleteData('mybb_sessions', 'uid='.$this->getCurUserId(), true);

                    MySql::getInstance()->insertData(
                        'mybb_sessions',
                        array(
                            'sid' => $t->SessId,
                            'uid' => $t->Uid,
                            'location' => getAbsPath().getPage(),
                            'useragent' => substr($_SERVER['HTTP_USER_AGENT'], 0, 100)
                        )
                    );
                }
            }

            cookie('mybb_sid', $t->SessId);
            cookie('mybb_mybbuser', $t->Uid.'_'.$t->LoginKey);

            redirectAJAX((isset($_POST['prev_site'])) ? base64_decode($_POST['prev_site']) : getAbsPath());

            getLogger()->addNotice("User logged in: ".$t->Uid." - ".$t->Name);

            return 'success';
        }

        return 'Invalid Username/Password combination!';
    }

    /**
     * Prints a link to the login page
     * @param string $text The label for the link
     * @param bool $append__current_page Whether to append the current page for return or not
     */
    public function linkToLogIn($text = '<b>login</b>', $append__current_page = true)
    {
        ob_start();
        link_to('user/login/index' . (($append__current_page) ? '/'.base64_encode(getPage()) : ''), $text);
        return ob_get_clean();
    }

    public function logout()
    {
        if ($this->getCurUserId()) {
            MySql::getInstance()->deleteData('mybb_sessions', 'uid='.$this->getCurUserId(), true);

            getLogger()->addNotice("User logged out: ".$this->getCurUserId()." - ".$this->getCurUserName());
        }
    }
}
