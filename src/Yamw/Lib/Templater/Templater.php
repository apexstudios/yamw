<?php
namespace Yamw\Lib\Templater;

use Yamw\Lib\Config;
use Yamw\Lib\Core;
use Yamw\Lib\TemplateHelper;
use Yamw\Lib\UAM\UAM;

class Templater
{
    private static $template_name;
    private static $template_code;
    private static $proc_crit;
    private static $proc_uncrit;

    public static function loadTemplate($template)
    {
        $path = path('Templates/'.$template.'/layout.php');
        if (!file_exists($path) || !is_readable($path)) {
            return false;
        }
        self::$template_name = $template;
        self::$template_code = file_get_contents($path);

        return true;
    }

    public static function noTemplate()
    {
        return static::loadTemplate(Config::get('template.default.nt'));
    }

    public static function isUsingTemplate()
    {
        return static::getCurrentTemplate() == Config::get('template.default.nt');
    }

    public static function getCurrentTemplate()
    {
        return static::$template_name;
    }

    public static function generateTemplate()
    {
        if (empty(self::$template_code) && !self::loadTemplate(Config::get('template.default'))) {
            throw new \RuntimeException('No template could be loaded!');
        }

        if (!defined('RET')) {
            define('RET', 'return ');
        }

        $constant_map = include path('constant_map.php');
        use_style('res/css/'.($constant_map['css_hash'] ?: rand_string()));
        TemplateHelper::use_js(
            'res/js/'.($constant_map['js_hash'] ?: rand_string())
        );

        self::processTemplate(false);
        self::processTemplate(true);
    }

    public static function loadCache($cache)
    {
        self::$template_name = 'cached';
        self::$template_code = $cache;
        self::$proc_uncrit = true;
    }

    public static function generateCache()
    {
        if (
            empty(self::$template_code) ||
            !self::loadTemplate(Config::get('template.default'))
        ) {
            throw new RuntimeException('No template could be loaded!');
        }

        if (!defined('RET')) {
            define('RET', 'return ');
        }

        self::processTemplate(false);
    }

    public static function processTemplate($crit = true)
    {
        global $num_queries;

        if (($crit ? self::$proc_crit : self::$proc_uncrit)) {
            return false;
        }

        $c = array(
            'CHAT' => RET.'Core::getInstance()->getModule("home", "index", "chat")',
            'MENU_USER' => RET.'\Yamw\Lib\MenuMgr::getMenuUser()',
            'JS_END' => RET.'includePartialJs(true)',
            'JS_NOTICES' => RET.'getNotices(true)',
            'STYLES' => RET.'TemplateHelper::includePartialCSS(true)',
            'ROOT' => RET.'"'.getAbsPath().'"',
            'FORUM' => RET.'"http://hmh.burningreality.de/forum/"',
            'CURUSER_ID' => RET."UAM::getInstance()->getCurUserId()",
            'CURUSER_NAME' => RET."UAM::getInstance()->getCurUserName()",
            'GENTIME' => RET.'getTime()',
            'PEAKMEM' => RET.'round(getMemoryUsage(), 2)',
            'NUM_QUERIES' => RET.$num_queries,
        );

        $u = array(
            'TITLE' => RET.'"'.include_slot('title', 'YAMW Systems Website').'"',
            'CONTENT' => RET.'Core::getInstance()->getContent()',
            'MENU_TOP' => RET.'\Yamw\Lib\MenuMgr::getMenuTop()',
            'MENU_MAIN' => RET.'\Yamw\Lib\MenuMgr::getMenuMain()',
            'VERSION' => RET."'".VERSION."'",
            'JS_FILES' => RET.'TemplateHelper::include_js_files(true)',
            'JS_BEGIN' => '',
            'STYLESHEETS' => RET.'include_styles(true)',
            'META' => RET.'include_metas(true)',
        );

        foreach (($crit ? $c : $u) as $patt => $eval) {
            $pattern = '{'.$patt.'}';
            if (strpos(self::$template_code, $pattern) !== false) {
                self::$template_code = str_replace(
                    $pattern,
                    eval("use Yamw\Lib\Core;use Yamw\Lib\UAM\UAM;
                        use Yamw\Lib\TemplateHelper;\n".$eval.';'),
                    self::$template_code
                );
            }
        }

        if ($crit) {
            self::$proc_crit = true;
        } else {
            self::$proc_uncrit = true;
        }
    }

    public static function retrieveTemplate()
    {
        return self::$template_code;
    }
}
