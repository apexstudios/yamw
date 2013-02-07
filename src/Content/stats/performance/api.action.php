<?php
use Yamw\Lib\Mongo\AdvMongo;
use Yamw\Lib\Mongo\MapReduce;

$avgmem = MapReduce::getAvgPerPage('api');
$stats = AdvMongo::getConn()->selectCollection($avgmem['result'])->find()->sort(array('statgroup' => 1));

$action = 'index';
