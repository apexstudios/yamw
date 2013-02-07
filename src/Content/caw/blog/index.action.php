<?php
use Yamw\Lib\UAM\UAM;
use Yamw\Lib\MySql\AdvMySql;

$blog = AdvMySql::getTable('mybb_posts')
        ->leftJoin('forum_cache', 'mybb_posts.pid = hcaw_forum_cache.id')
        ->where('fid', 2)->addOr()->where('fid', 3)
        ->groupby('tid')
        ->orderby('dateline', DESC)
        ->limit(6)
        ->setModel('Blog')
        ->execute();

$users = array();

foreach ($blog as $b) {
    if (!UAM::getInstance()->Users()->UserLoaded($b->getAuthorId())) {
        $users[] = $b->getAuthorId();
    }
}

UAM::getInstance()->Users()->retrieveUsers($users);
