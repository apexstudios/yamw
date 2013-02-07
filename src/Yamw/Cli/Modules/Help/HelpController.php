<?php
namespace Yamw\Cli\Modules\Help;

use Yamw\Cli\Interfaces\Help;
use Yamw\Cli\Lib\Cli\Cli;
use Yamw\Lib\Loaders\CliLoader;

class HelpController
{
    public function mainAction()
    {
        print <<<EOT
Usage: yamw [options] [cli_module] [action] [section]
EOT;
    }

    public function __call($name, $args)
    {
        if (preg_match("/Action$/", $name)) {
            $name = preg_replace("/Action$/", "", $name);
        }

        println("You requested help with $name");

        $module = functionify($name);
        $moduleClass = "\\Yamw\\Cli\\Modules\\{$module}\\{$module}Controller";
        if (!class_exists($moduleClass, true)) {
            println("No help for $name was not found!");
            exit();
        }

        $module = new $moduleClass;

        if (!($module instanceof Help)) {
            Cli::fatal("$name won't help you because it does not know any help!");
        }

        $module->help();
    }
}
