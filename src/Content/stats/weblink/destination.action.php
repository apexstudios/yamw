<?php
use Yamw\Lib\Mongo\AdvMongo;
use Yamw\Lib\Mongo\MapReduce;

$weblink_mr = MapReduce::getRevWebLink();
$weblink = AdvMongo::getConn()->selectCollection($weblink_mr['result'])->find();
