<?php
/*
 * $HeadURL$
 *
 * @author  Anh Nhan <anhnhan@outlook.com>
 * @version SVN: $Revision$
 */

use Yamw\Lib\Core;
use Yamw\Lib\Mongo\Stats;
use YamwLibs\Infrastructure;
use YamwLibs\Infrastructure\Templater\Templater;

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

Infrastructure\Config\Config::setConfigPath(path("config/config.php"));
Templater::setTemplatePrefix(path("Templates/"));
$markupMgr = new Infrastructure\Templater\MarkupManager;

Core::getInstance()->register();

Custom\Templater\MarkupRegistrar::registerMarkups($markupMgr);
Templater::setMarkupMgr($markupMgr);
Templater::generateTemplate();
echo Templater::retrieveTemplate();

// Stats
Stats::save();
