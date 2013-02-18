<?php
namespace Custom\Templater;

use YamwLibs\Infrastructure\Templater\MarkupManager;
use YamwLibs\Infrastructure\Templater\Markup\SimpleTemplateMarkup;
use YamwLibs\Infrastructure\Templater\Markup\MethodInvocationMarkup;
use Yamw\Lib\Core;
use Yamw\Lib\UAM\UAM;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Custom
 */
class MarkupRegistrar
{
    public static function registerMarkups(MarkupManager $mgr)
    {
        // Stub
        global $num_queries;
        $simple = array(
            'JS_BEGIN' => '',
            'VERSION' => VERSION,
            'FORUM' => 'http://hmh.burningreality.de/forum/',
            'NUM_QUERIES' => $num_queries,
        );

        foreach ($simple as $key => $value) {
            self::registerSimpleMarkup($mgr, $key, $key, $value, false);
        }

        $methods = array(
            'STYLESHEETS' => array(
                'include_styles',
                array(true),
            ),
            'META' => array(
                'include_metas',
                array(true),
            ),
            'TITLE' => array(
                'include_slot',
                array('title', 'YAMW Systems Website'),
            ),
            'ROOT' => array(
                'getAbsPath',
                array(),
            ),
            'JS_END' => array(
                'includePartialJs',
                array('title', 'YAMW Systems Website'),
            ),
            'PEAKMEM' => array(
                'getMemoryUsage',
                array(),
            ),
            'GENTIME' => array(
                'getTime',
                array(),
            ),


            'STYLES' => array(
                array('Yamw\Lib\TemplateHelper', 'includePartialCSS'),
                array(true),
            ),
            'MENU_USER' => array(
                array('Yamw\Lib\MenuMgr', 'getMenuUser'),
                array(),
            ),
            'MENU_TOP' => array(
                array('Yamw\Lib\MenuMgr', 'getMenuTop'),
                array(),
            ),
            'MENU_MAIN' => array(
                array('Yamw\Lib\MenuMgr', 'getMenuMain'),
                array(),
            ),
            'JS_FILES' => array(
                array('Yamw\Lib\TemplateHelper', 'include_js_files'),
                array(true),
            ),


            'CHAT' => array(
                array(Core::getInstance(), 'getModule'),
                array("home", "index", "chat"),
            ),
            'CONTENT' => array(
                array(Core::getInstance(), 'getModule'),
                array(),
            ),

            'CURUSER_ID' => array(
                array(UAM::getInstance(), 'getCurUserId'),
                array(),
            ),
            'CURUSER_NAME' => array(
                array(UAM::getInstance(), 'getCurUserName'),
                array(),
            ),
        );

        foreach ($methods as $key => $value) {
            self::registerMethodMarkup($mgr, $key, $key, $value, true);
        }
    }

    public static function registerSimpleMarkup(MarkupManager $mgr, $name, $pattern, $content, $critical)
    {
        $mgr->registerMarkup(
            new SimpleTemplateMarkup($name, $pattern, $content, $critical)
        );
    }

    public static function registerMethodMarkup(MarkupManager $mgr, $name, $pattern, array $method, $critical)
    {
        $mgr->registerMarkup(
            new MethodInvocationMarkup($name, $pattern, $method, $critical)
        );
    }
}
