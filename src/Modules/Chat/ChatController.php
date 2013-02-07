<?php
namespace Modules\Chat;

use \Yamw\Lib\MySql\AdvMySql;
use \Yamw\Lib\Config;

/**
 * Performs the actions required for Chat functionality
 *
 * @author AnhNhan <anhnhan@outlook.com>
 *
 */
class ChatController extends \Yamw\Modules\RootController
{
    public function indexAction()
    {
        $chat = $this->fetchEntries();
        ChatBuilder::push($chat);
    }

    /**
     *
     */
    public function fetchEntries($num_entries = null)
    {
        if ($num_entries === null) {
            $num_entries = Config::get('chat.maxentries');
        }

        return AdvMySql::getTable('chat')->orderby('id')
            ->limit($num_entries)->execute();
    }
}
