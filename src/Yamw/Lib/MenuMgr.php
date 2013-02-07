<?php
namespace Yamw\Lib;

use \Yamw\Lib\MySql\AdvMySql;
use \Yamw\Lib\Builders\Markup\HtmlTag;

class MenuMgr
{
    private static $instance;

    private $menus = array();

    /**
     * Returns the MenuMgr-instance used to manage all Menu-objects
     */
    final public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    /**
     * Do NOT call this constructor!
     */
    private function __construct()
    {
        // Do nothing
    }

    public static function getMenuTop()
    {
        $menus = static::getInstance()->getMenu('sections')->getMenuEntries();
        $menu = new HtmlTag('ul');

        foreach ($menus as $entry) {
            $menu->appendContent(
                new HtmlTag(
                    'li',
                    $entry->getLink(false)
                )
            );
        }

        return $menu;
    }

    public static function getMenuMain($name = null)
    {
        $name = $name === null ? include_slot('menu', 'hub') : $name;
        $menus = static::getInstance()->getMenu($name)->getMenuEntries();
        $menu = new HtmlTag('ul', null, array('class' => 'menu'));

        foreach ($menus as $entry) {
            $menu->appendContent(
                new HtmlTag(
                    'li',
                    $entry->getLink(false)
                )
            );
        }

        return $menu;
    }

    public static function getMenuUser()
    {
        $usersys = new HtmlTag('div', null, array('id' => 'usersys'));

        if (UAM\UAM::getInstance()->isLoggedIn()) {
            $link = UAM\UAM::getInstance()->getCurUserLink();
            $usersys->setContent('Hello, '.$link.
                '. Go to the <a href="{FORUM}usercp.php"><b>user cp</b></a>');

            if(UAM\UAM::getInstance()->getCurUserIsAdmin()) {
                $usersys->appendContent(
                    ', the <a href="admin"><b>admin panel</b></a>'
                );
            }

            $usersys->appendContent(
                ' or <a href="user/logout"><b>log out</b></a>'
            );
        } else {
            $link = UAM\UAM::getInstance()->linkToLogin();
            $usersys->setContent(
                'Hello, <b>Guest</b>. Please '.
                '<a href="{FORUM}member.php?action=register">'.
                '<b>register</b></a> or '.$link
            );
        }

        return $usersys;
    }

    public function getMenu($name)
    {
        if (!is_string($name) || !$name) {
            return false;
        }

        if (isset($this->menus[$name])) {
            return $this->menus[$name];
        }

        $m = AdvMySql::getTable('menu')->where('menuname', $name)->execute();
        if (!count($m)) {
            trigger_error("Warning! Menu {$name} does not exist!", E_USER_WARNING);
            return false;
        }

        $this->menus[$m[0]['menuname']] = new Menu((int)$m[0]['mid']);

        return $this->menus[$m[0]['menuname']];
    }
}
