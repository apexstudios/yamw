<?php
/*
 * $HeadURL$
 *
 * @author  Anh Nhan <anhnhan@outlook.com>
 * @version SVN: $Revision$
 */

use Yamw\Lib\Core;
use Yamw\Lib\TemplateHelper;
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

$constant_map = include path('constant_map.php');
TemplateHelper::use_style('res/css/'.($constant_map['css_hash'] ?: rand_string()));
TemplateHelper::use_js('res/js/'.($constant_map['js_hash'] ?: rand_string()));

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
