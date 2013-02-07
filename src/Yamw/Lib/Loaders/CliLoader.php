<?php
namespace Yamw\Lib\Loaders;

use Yamw\Cli\Lib\Cli\Cli;
use Yamw\Cli\Lib\Color\Color;
use Yamw\Lib\Config;

/**
 * The CliLoader class
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Loaders
 */
class CliLoader
{
    private $args;
    private $opts;
    private $parsedArgs;
    private $module;
    private $action;
    private $section;

    public function __construct(array $args)
    {
        $this->parsedArgs = $this->parseArgv($args);
        $this->args = $this->parsedArgs[0];
        $this->opts = $this->parsedArgs[1];

        if(array_key_exists('color', $this->opts)) {
            Color::$color = true;
        }

        $this->module = isset($this->args[0]) ? $this->args[0] : Config::get('cli.default.module');
        $this->action = isset($this->args[1]) ? $this->args[1] : Config::get('cli.default.action');
        $this->section = isset($this->args[2]) ? $this->args[2] : Config::get('cli.default.section');

        ob_start();
        println();
        println(VERSION." on ".CURRENT_OS);
        println();
    }

    public function load()
    {
        $module = functionify($this->module);
        $moduleClass = "\\Yamw\\Cli\\Modules\\{$module}\\{$module}Controller";
        if (!class_exists($moduleClass, true)) {
            Cli::error("Module $module was not found");
            $moduleClass = "\\Yamw\\Cli\\Modules\\Help\\HelpController";
            $this->action = "main";
        }
        $module = new $moduleClass($this->args, $this->opts);

        $action = $this->action.'Action';

        if (method_exists($moduleClass, $action)) {
            $module->$action();
        } else {
            Cli::error("Action {$this->action} was not found for this module");
            $module->mainAction();
        }
        
        println();
        return ob_get_clean();
    }

    public function parseArgv(array $argv)
    {
        if (!$argv) {
            return array(array(), array());
        }
        array_map('trim', $argv);

        // $key => $value
        $opts = array();

        // $i   => $value
        $nonopts = array();

        foreach ($argv as $i => $arg) {
            if ($arg[0] == '-') {
                $key = trim(array_shift($argv), '-');

                switch ($key) {
                    case 'color':
                    case 'cli':
                    case 'action':
                    case 'only':
                        // Std value, dummy
                        $val = true;
                        break;
                    default:
                        $val = array_shift($argv);
                        break;
                }

                $opts[$key] = $val;
            } else {
                $nonopts[] = array_shift($argv);
            }
        }

        return array($nonopts, $opts);
    }
}
