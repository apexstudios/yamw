<?php
use Yamw\Lib\Mongo\AdvMongo;
use Yamw\Lib\Mongo\MapReduce;

$avgmem = MapReduce::getAvgPerPage();
$stats = AdvMongo::getConn()->selectCollection($avgmem['result'])->find();
