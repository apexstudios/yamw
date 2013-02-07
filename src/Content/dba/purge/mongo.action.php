<?php
use Yamw\Lib\Mongo\AdvMongo;

$mongo = array(
    'yamw_stats',
    'thumbs'
);

foreach ($mongo as $col) {
    AdvMongo::getConn()->$col->remove();
}
