<?php
namespace Yamw\Lib\UAM;

use \Yamw\Lib\MySql\AdvMySql;

/**
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage UAM
 */
final class UAMStorage
{
    private static $instance;
    private $data_users = array();
    private $data_groups = array();

    final public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    private function __construct()
    {

    }

    public function userLoaded($uid)
    {
        return isset($this->data_users[$uid]);
    }

    // ********************************************************* //
    //                       User Part                           //
    // ********************************************************* //

    /**
     * @param int $uid
     * @return array The requested user, or false upon error
     */
    public function getUserById($uid)
    {
        if ($uid == 0) {
            return false;
        }

        if (!isset($this->data_users[$uid])) {
            $this->retrieveUsers(array($uid));
        }

        return isset($this->data_users[$uid]) ? $this->data_users[$uid] : false;
    }

    public function getUserNameById($id)
    {
        return $id ? $this->getAttrById($id, 'username') : 'Guest';
    }

    public function getUserSessIdById($id)
    {
        if (isset($this->data[$id]['sid'])) {
            return @$this->getAttrById($id, 'sid');
        } else {
            return null;
        }
    }

    /**
     *
     * @param string $sessid the sessid to be checked
     * @return int the uid of the guy having it
     */
    public function checkForSessId($sessid)
    {
        if (!is_string($sessid) || !$sessid) {
            return false;
        }

        foreach ($this->data_users as $user) {
            if (
                @isset($this->data_users[$user['uid']]['sid']) &&
                $sessid == $this->data_users[$user['uid']]['sid']
            ) {
                return $user['uid'];
            }
        }
    }

    public function getSaltById($id)
    {
        return $this->getAttrById($id, 'salt');
    }

    public function getLoginKeyById($id)
    {
        return $this->getAttrById($id, 'loginkey');
    }

    public function getUserEMailById($id)
    {
        return $this->getAttrById($id, 'email');
    }

    public function getUserAvatarById($id)
    {
        return $this->getAttrById($id, 'avatar');
    }

    public function getUserGroupId($id)
    {
        return $this->getAttrById($id, 'usergroup');
    }

    public function getUserGroupNameById($id)
    {
        return $this->getGroupNameById($this->getUserGroupId($id));
    }

    public function isAdminById($id)
    {
        return $this->isGroupAnAdminById($this->getUserGroupId($id));
    }

    // ********************************************************* //
    //                       Group Part                          //
    // ********************************************************* //

    public function getGroupById($gid)
    {
        if (!isset($this->data_groups[$gid])) {
            $this->retrieveGroups(array($gid));
        }

        return isset($this->data_groups[$gid]) ? $this->data_groups[$gid] : false;
    }

    public function getGroupNameById($id)
    {
        return $this->getAttrById($id, 'title', false);
    }

    public function isGroupAnAdminById($id)
    {
        return ($id == 4 || $id == 8);
    }


    // ********************************************************* //
    //                     Storage Part                          //
    // ********************************************************* //

    protected function getBoolAttrById($id, $attr)
    {
        return checkForTrue($this->getAttrById($id, $attr));
    }

    protected function getAttrById($id, $attr, $user = true)
    {
        $id = (int)$id;
        if (($user ? !isset($this->data_users[$id]) : !isset($this->data_groups[$id]))) {
            if ($user ? $this->retrieveUsers(array($id)) : $this->retrieveGroups(array($id))) {
                // De nothing
            } else {
                trigger_error('$id '.$id.' invalid');
                return false;
            }
        }

        $attr = (string)$attr;
        if (!$attr || @!($user ? $this->data_users[$id][$attr] : $this->data_groups[$id][$attr])) {
            trigger_error('$attr '.$attr.' invalid');
            return false;
        }
        return $user ? $this->data_users[$id][$attr] : $this->data_groups[$id][$attr];
    }

    /**
     * Retrieves one or multiple users from the Database and adds them to the internal storage
     * @param int $uids
     * @return bool
     */
    public function retrieveUsers(array $uids)
    {
        if (!is_array($uids) || !count($uids)) {
            return false;
        }

        $result = array();

        $result = AdvMySql::getTable('mybb_users')
            ->select(
                'mybb_users.uid,mybb_sessions.sid,username,password,salt,loginkey'.
                ',email,avatar,avatardimensions,avatartype,usergroup,usertitle'
            )
            ->doNotPrependTableName()
            ->leftJoin('mybb_sessions', 'mybb_users.uid = mybb_sessions.uid')
            ->where('mybb_users.uid', $uids)->execute();


        if (!count($result)) {
            return false;
        }

        foreach ($result as $x => $y) {
            $this->data_users[$y['uid']] = $y;
        }

        return true;
    }

    /**
     * Retrieves one or multiple groups from the Database and adds them to the internal storage
     * @param int $gids
     * @return bool
     */
    public function retrieveGroups(array $gids)
    {
        if (!is_array($gids) || !count($gids)) {
            return false;
        }

        $r = array();
        $r = AdvMySql::getTable('mybb_usergroups')->where('uid', $gids)->execute();

        if (!count($r)) {
            return false;
        }

        foreach ($r as $x => $y) {
            $this->data_groups[$y['gid']] = $y;
        }

        return true;
    }
}
