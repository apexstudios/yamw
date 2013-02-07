<?php
namespace Yamw\Cli\Modules\Build;

use Yamw\Cli\Lib\Cli\Cli;
use Yamw\Cli\Interfaces\Help;
use Yamw\Cli\Root\RootController;

class BuildController extends RootController implements Help
{
    public function mainAction()
    {
        // TODO: Empty method stub
        $this->help();
    }

    public function help()
    {
        // TODO: Empty help stub
        Cli::fatal("No doc available");
    }
}
