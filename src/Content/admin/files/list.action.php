<?php
use Yamw\Lib\Mongo\AdvMongo;

nocache();
$gallery = AdvMongo::gridFs('gallery')->find();
$media  = AdvMongo::gridFs('media')->find();
