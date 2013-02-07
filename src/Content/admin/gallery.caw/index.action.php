<?php
use Yamw\Lib\Request;
use Yamw\Lib\Mongo\AdvMongo;

$page = Request::exists('id') ? Request::get('id') : 0;
$gallery = AdvMongo::gridFs('gallery')->find(array('metadata.section' => 'caw'))
    ->limit(10)->skip($page * 10);
