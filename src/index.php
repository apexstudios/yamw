<?php
/*
 * $HeadURL$
 *
 * @author  Anh Nhan <anhnhan@outlook.com>
 * @version SVN: $Revision$
 */

use \Yamw\Lib\Core;
use \Yamw\Lib\Templater\Templater;
use \Yamw\Lib\Mongo\Stats;

global $start_time;
$start_time = microtime(true);

global $num_queries;
$num_queries = 0;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/inc.php';

require_once __DIR__.'/Yamw/Lib/Constants.php';
require_once __DIR__.'/Yamw/Lib/Settings.php';

useHelper('Template');
useHelper('Forward');
useHelper('Security');
useHelper('BBCode');

Core::getInstance()->register();

Templater::generateTemplate();
echo Templater::retrieveTemplate();

// Stats
Stats::save();
