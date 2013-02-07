<?php
namespace Yamw\Cli\Modules\Compile;

use Yamw\Cli\Lib\Cli\Cli;
use Yamw\Cli\Lib\Color\Color;
use Yamw\Cli\Interfaces\Help;
use Yamw\Cli\Root\RootController;
use Yamw\Lib\ResourceManagement\ResMgr;

class CompileController extends RootController implements Help
{
    public function mainAction()
    {
        $this->allAction();
    }

    public function allAction()
    {
        $this->cssAction();
        $this->jsAction();
    }

    public function cssAction()
    {
        println();
        Cli::output("Compiling css resources.");

        $resources = array(
            'css/less/main.less'
        );
        $compiler = ResMgr::compileAndSave('css');
        foreach ($resources as $value) {
            Cli::notice("Adding ".Color::blue($value)." to the resource stack");
            $compiler->pushResource($value);
        }
        $hash = $compiler->compile();

        if ($hash) {
            Cli::success("Css resources where compiled successfully.");
            Cli::notice("Css hash is: " . Color::blue($hash));
        } else {
            Cli::error("Apparantly could not compile css resource stack!");
        }
    }

    public function jsAction()
    {
        println();
        Cli::output("Compiling javascript resources.");

        $resources = array(
            'js/jquery-1.8.3.min.js',
            'js/jquery.effects.core.min.js',
            'js/jquery.effects.blind.min.js',
            'js/jquery.effects.bounce.min.js',
            'js/jquery.effects.drop.min.js',
            'js/jquery.effects.fade.min.js',
            'js/jquery.effects.highlight.min.js',
            'js/jquery.effects.pulsate.min.js',
            'js/jquery.extras.min.js',
            'js/mbExtruder.min.js',
            'js/jquery.easing.1.3.js',
            'js/jquery.blockUI.js',
            'js/jquery.notice.js',
        );

        $compiler = ResMgr::compileAndSave('js');
        foreach ($resources as $value) {
            Cli::notice("Adding ".Color::blue($value)." to the resource stack");
            $compiler->pushResource($value);
        }
        $hash = $compiler->compile();

        if ($hash) {
            Cli::success("Javascript resources where compiled successfully.");
            Cli::notice("Javascript hash is: " . Color::blue($hash));
        } else {
            Cli::error("Apparantly could not compile javascript resource stack!");
        }
    }

    public function help()
    {
        // TODO: Empty help stub
        Cli::fatal("No doc available");
    }
}
