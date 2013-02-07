<?php
namespace Yamw\Lib\Loaders;

/**
 * The ModuleLoader class
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Loaders
 */
class ModuleLoader
{
    protected static $routings = array();

    protected $module;
    protected $action;
    protected $section;

    public function __construct()
    {
        if (!static::$routings) {
            $this->loadRoutings();
        }
    }

    public function load($module, $action, $section)
    {
        $this->module = $module;
        $this->action = $action;
        $this->section = $section;

        // Iterate over each route
        foreach (static::$routings as $route) {
            switch (count($route)) {
                case 1:
                    list($key, $val) = $route;
                    if ($$key == $val) {
                        $this->loadModule();
                        return;
                    }
                    break;
                case 2:
                    $key1 = key($route);
                    $val1 = current($route);
                    next($route);
                    $key2 = key($route);
                    $val2 = current($route);

                    if ($$key1 == $val1 && $$key2 == $val2) {
                        $this->loadModule();
                        return;
                    }
                    break;
                case 3:
                    // TODO: Write studd for third one
                    break;
                default:
                    break;
            }
        }

        $this->loadContent();
    }

    protected function loadContent()
    {
        $module = $this->module;
        $action = $this->action;
        $section = $this->section;

        // If it exists, load the Section configuration
        $sectionConfig = 'Content/'.$section.'/Section.config.php';
        if(file_exists($sectionConfig)) {
            include_once path($sectionConfig);
        }
        // If it exists, load the Module configuration
        $moduleConfig = 'Content/'.$section.'/'.$module.'/Module.config.php';
        if(file_exists($moduleConfig)) {
            include_once path($moduleConfig);
        }

        // Load and execute the action
        $actionPath = 'Content/'.$section.'/'.$module.'/'.$action.'.action.php';
        if(file_exists($actionPath)) {
            include path($actionPath);
        } else {
            throw new \Yamw\Lib\Exceptions\HttpErrorException("The action $action was not found!", 404);
        }

        // Load the module content itself
        $modulePath = 'Content/'.$section.'/'.$module.'/'.$action.'.template.php';
        if(file_exists($modulePath)) {
            include path($modulePath);
        } else {
            throw new \Yamw\Lib\Exceptions\HttpErrorException("The module $module was not found!", 404);
        }
    }

    protected function loadModule()
    {
        $module = $this->module;
        $action = $this->action;
        $section = $this->section;

        $modules = functionify($module);
        $moduleClass = "\\Modules\\{$module}\\{$module}Controller";
        if (!class_exists($moduleClass, true)) {
            throw new \Yamw\Lib\Exceptions\HttpErrorException("The module $module was not found!", 404);
        }
        $module = new $moduleClass();

        $actions = $action.'Action';

        if (!method_exists($moduleClass, $actions)) {
            throw new \Yamw\Lib\Exceptions\HttpErrorException("The action $action was not found!", 404);
        }

        $module->$actions();
    }

    protected function loadRoutings()
    {
        static::$routings = include path('config/moduleroutings.php');
    }
}
