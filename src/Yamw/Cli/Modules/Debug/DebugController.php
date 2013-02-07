<?php
namespace Yamw\Cli\Modules\Debug;

use Yamw\Cli\Lib\Cli\Cli;
use Yamw\Cli\Interfaces\Help;
use Yamw\Cli\Root\RootController;
use Yamw\Lib\Core;
use Yamw\Lib\Request;

class DebugController extends RootController implements Help
{
    public function mainAction()
    {
        Core::getInstance()->http();
        echo Core::getInstance()->getModule(@$this->args[0], @$this->args[1], @$this->args[2]);
    }

    public function help()
    {
        print <<<EOT
    Usage: yamw debug [module]
EOT;
    }
}
