<?php
namespace Yamw\Models;

use \Yamw\Lib\Modelizer\Modelizer_Base;
use \Yamw\Lib\Interfaces\ModelizerInterface;
use \Yamw\Lib\MySql\AdvMySql;

class MybbUser extends Modelizer_Base implements ModelizerInterface
{
    protected static $table = 'mybb_user';
    protected static $id_column = 'uid';
    protected static $array_map = array(
                    'Uid' => 'uid',
                    'SessId' => 'sid',
                    'Name' => 'username',
                    'LoginKey' => 'loginkey',
                    'Salt' => 'salt'
                    );

    public static function retrieve($var)
    {
        if (!is_numeric($var)) {
            // We have a username
            $field = 'username';
        } else {
            // We have a uid
            $field = 'uid';
        }

        $t = AdvMySql::getTable('mybb_users')->doNotPrependTableName()
            ->leftJoin('mybb_sessions', 'mybb_users.uid = mybb_sessions.uid')
            ->select('mybb_users.uid,mybb_sessions.sid,username,password,salt,loginkey,usergroup')
            ->where($field, $var)->limit(1)->execute();

        if (!count($t)) {
            return false;
        } else {
            return new MybbUser($t[0]);
        }
    }

    /**
     * @return bool Whether the Credentials supplied are valid
     */
    public function validateCredentials($password)
    {
        useHelper('MyBB');

        if (!$this->LoginKey) {
            $this->LoginKey = mybb_generate_loginkey();
            $sql_array = array(
                            "loginkey" => $this->LoginKey
            );
            \Yamw\Lib\MySql\MySql::getInstance()->updateData('mybb_users', $sql_array, "uid='".$this->Uid."'");
        }

        if ($this->saltPassword($password) == $this->data['password']) {
            return true;
        } else {
            return false;
        }
    }

    private function saltPassword($pw)
    {
        if (!$this->Salt) {
            // Generate a salt for this user and assume the password stored in db is a plain md5 password
            $this->Salt = mybb_generate_salt();
            $this->data['password'] = mybb_salt_password($this->data['password'], $this->Salt);
            $sql_array = array(
                            "salt" => $this->Salt,
                            "password" => $this->data['password']
            );
            \Yamw\Lib\MySql\MySql::getInstance()->updateData(
                $this->getTable(),
                $sql_array,
                "uid='".$this->Uid."'"
            );
        }

        return md5(md5($this->Salt).md5($pw));
    }

    private function getExternals()
    {

    }
}
