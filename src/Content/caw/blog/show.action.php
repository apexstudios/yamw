<?php
use Yamw\Lib\Request;
use Yamw\Lib\MySql\AdvMySql;

forward404Unless(Request::exists('id'));


$blog = AdvMySql::getTable('mybb_posts')
                            ->setModel('Blog')
                            ->leftJoin('forum_cache', 'mybb_posts.pid = hcaw_forum_cache.id')
                            ->where('pid', Request::get('id'))
                            ->limit(1)
                            ->execute();
$blog = $blog[0];
forward404Unless($blog);
