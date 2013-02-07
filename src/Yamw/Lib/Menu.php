<?php
namespace Yamw\Lib;

use \Yamw\Lib\MySql\AdvMySql;

class Menu
{
    private $menu_id;
    private $menu_entries = array();

    public function __construct($id)
    {
        if (!is_int($id) || !$id) {
            return false;
        }

        $m = AdvMySql::getTable('menu_entries')
            ->where('mid', $id)
            ->orderby('sort', ASC)
            ->setModel('MenuEntry')
            ->execute();
        if (count($m)) {
            $this->menu_entries = $m;
        } else {
            trigger_error("Warning! Menu {$id} does not have any entries!", E_USER_NOTICE);
        }
    }

    /**
     * Returns the menu entries associated with this menu
     * @return array
     * <p>The MenuEntry-objects as an array</p>
     */
    public function getMenuEntries()
    {
        return $this->menu_entries;
    }
}
