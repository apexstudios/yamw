<?php
namespace Yamw\Cli\Modules\Modules;

use Yamw\Lib\Parsers\ModuleParser;

use Yamw\Cli\Lib\Cli\Cli;
use Yamw\Cli\Interfaces\Help;
use Yamw\Cli\Root\RootController;

class ModulesController extends RootController implements Help
{
    public function createAction()
    {
        $name = ucwords($this->action);
        println("Creating module $name");

        $isCli = $this->getOpt('cli');

        $dirpath = $isCli ?
            path('Yamw/Cli/Modules/'.$name.'/') :
            path('Modules/'.$name.'/');

        $filepath = $dirpath . "/" . $name . "Controller.php";

        if (!file_exists($dirpath) || !is_dir($dirpath)) {
            Cli::notice('Directory '.$dirpath
                .' does not exist. Will attempt to create it');

            if (!is_writeable(dirname($dirpath))) {
                Cli::fatal("Directory not writeable");
            } else {
                if (mkdir($dirpath)) {
                    Cli::success("Directory created successfully");
                } else {
                    Cli::fatal("Directory could not be created");
                }
            }
        }

        if (file_exists($filepath)) {
            Cli::fatal("Module file already exists");
        }

        if (!is_writeable($dirpath)) { // || !touch($filepath)
            Cli::fatal("Could not create module file".
                ", probably due to permissions");
        }

        $parser = new ModuleParser($name, $dirpath, $isCli);

        if ($this->getOpt('action')) {
            $actions = $this->nonopts;

            // Removing std stuff
            for ($i = 0; $i <= 2; $i++) {
                array_shift($actions);
            }

            foreach ($actions as $action) {
                try {
                    $parser->addMethod($action);
                    Cli::notice("Created action $action");
                } catch (\InvalidArgumentException $e) {
                    Cli::error($e->getMessage().". Of course, we are continueing ^.^");
                } catch (\RuntimeException $e) {
                    Cli::fatal($e->getMessage());
                }
            }
        } else {
            $parser->addMethod($isCli ? 'main' : 'index');
        }

        $parser->saveGeneratedModule();

        if (
            file_exists($filepath) &&
            strlen(file_get_contents($filepath)) > 50 // Some reasonable number
        ) {
            Cli::success("Successfully created module $name");
        } else {
            Cli::fatal("Something went utterly wrong during creation");
        }
    }

    public function removeAction()
    {
        $name = ucwords($this->action);
        println("Removing module $name");

        $isCli = $this->getOpt('cli');

        $dirpath = $isCli ?
            path('Yamw/Cli/Modules/'.$name.'/') :
            path('Modules/'.$name.'/');

        $filepath = $dirpath . "/" . $name . "Controller.php";

        if (file_exists($filepath)) {
            function iterate($dir) {
                $folder = dir($dir);

                $files = array();

                while ($file = $folder->read()) {
                    if($file == '.' || $file == '..') {
                        continue;
                    }

                    $file = $dir.'/'.$file;

                    if(is_dir($file)) {
                        iterate($file);
                        Cli::notice("Folder - " . $file);
                        rmdir($file);
                    } else {
                        Cli::notice("File - " . $file);
                        unlink($file);
                    }
                }
                closedir($folder->handle);

                Cli::notice("Folder - " . $dir);
                rmdir($dir);
            }

            iterate($dirpath);
        } else {
            Cli::fatal("Module not found. Does it actually exist, stupid?");
        }
    }

    public function help()
    {
        print <<<EOT
Usage: yamw modules [action] module-name [options]

        Available Actions:
            create    Creates a new module
            remove    Removes an existing module

        Available Options:
            --action    Non-functional.
                        Performs the operation on an action
                        to a module, not the module itself
            --cli       Instead of modifying application
                        modules the changes will apply to cli modules
EOT;
    }
}
