<?php
namespace Yamw\Lib\Mongo;

final class Stats
{
    private static $instance;
    private static $statGroup;
    private static $saveStats = true;

    public static function saveStats($val = true)
    {
        static::$saveStats = $val;
    }

    public static function dontSaveStats()
    {
        static::saveStats(false);
    }

    public static function statGroup($group = null)
    {
        if ($group === null) {
            return static::$statGroup;
        } else {
            static::$statGroup = $group;
        }
    }

    public static function save()
    {
        if (!static::$saveStats || !class_exists('MongoClient') || isCli()) {
            return false;
        }

        global $num_queries, $Config, $Processer, $statgroup;
        $current_template = \Yamw\Lib\Templater\Templater::getCurrentTemplate();

        if (!static::statGroup() && $current_template == 'nt') {
            static::statGroup('nt');
        } elseif (!static::statGroup()) {
            static::statGroup('main');
        }

        if ($_SERVER['HTTP_USER_AGENT'] == 'ELB-HealthChecker/1.0') {
            static::statGroup('elb');
        }

        $time = explode(' ', microtime());

        return AdvMongo::getConn()->yamw_stats->insert(
            array(
                'time' => new \MongoDate($time[1], $time[0]),
                'page' => '/'.getPage(),
                'abspath' => getAbsPath(),
                'pagetime' => getTime(),
                'numqueries' => $num_queries,
                'max_memory' => getMemoryUsage(),
                'template' => $current_template,
                'uses_template' => $current_template != 'nt',
                'statgroup' => static::statGroup(),
                'global_request' => $_REQUEST,
                'global_get' => $_GET,
                'global_cookies' => $_COOKIE,
                'global_server' => $_SERVER
            )
        );
    }
}
