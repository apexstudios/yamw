<?php
namespace Yamw\Cli\Modules\Config;

use Yamw\Cli\Exceptions\ErrorException;
use Yamw\Cli\Interfaces\Help;
use Yamw\Cli\Lib\Cli\Cli;
use Yamw\Cli\Lib\Color\Color;
use Yamw\Cli\Root\RootController;
use Yamw\Lib\Config;
use Yamw\Lib\Parsers\ConfigParser;

class ConfigController extends RootController implements Help
{
    private $parser;

    public function __construct(
        array $nonopts = null,
        array $opts = null,
        $configpath = 'config/config.php'
    ) {
        parent::__construct($nonopts, $opts);

        // We don't need to parse the config file for help
        if (
            isset($this->nonopts[1]) &&
            $this->nonopts[1] != 'help' &&
            $this->nonopts[1] != 'main' &&
            $this->nonopts[1] != 'get'
        ) {
            $this->parser = new ConfigParser($configpath);
        }
    }

    public function getAction()
    {
        if (Config::get($this->action) == null) {
            Cli::fatal("Config entry ".Color::blue($this->action).
                " not found!");
        }

        Cli::success('Successfully retrieved config entry '.
            Color::blue($this->action));
        echo Config::get($this->action);
    }

    public function setAction()
    {
        forward404Unless(isset($this->action) && isset($this->nonopts[3]));

        $key = $this->action;
        $value = $this->nonopts[3];

        try {
            $this->parser->setEntry($key, $value);
            Cli::success('Successfully updated config entry '.
                Color::blue($this->action));
        } catch (ErrorException $e) {
            Cli::error($e->getMessage());
        }

        $this->saveConfig();
    }

    public function addAction()
    {
        forward404Unless(isset($this->action) && isset($this->nonopts[3]));

        $key = $this->action;
        $value = $this->nonopts[3];

        try {
            $this->parser->addEntry($key, $value);
            Cli::success('Successfully added config entry '.
                Color::blue($this->action));
        } catch (ErrorException $e) {
            Cli::error($e->getMessage());
        }

        $this->saveConfig();
    }

    public function removeAction()
    {
        forward404Unless(isset($this->action));

        try {
            $this->parser->removeEntry($this->action);
            Cli::success('Successfully removed config entry '.
                Color::blue($this->action));
        } catch (ErrorException $e) {
            Cli::error($e->getMessage());
        }
        $this->saveConfig();
    }

    public function listAction()
    {
        $config_file = include path('config/config.php');

        if (!$config_file) {
            Cli::fatal("Could not read config file");
        }

        if (!is_array($config_file)) {
            Cli::fatal("Something is utterly wrong with the config file");
        }

        $entries = array();

        if ($this->getOpt('only')) {
            $options = $this->nonopts;
            array_shift($options);
            array_shift($options);

            foreach ($config_file as $key => $value) {
                foreach ($options as $option) {
                    $option = preg_quote($option, '/');

                    if (strpos($option, '\*') !== false) {
                        $option = str_replace("\*", ".*", $option);
                    }

                    if (preg_match("/^$option/i", $key)) {
                        $entries[] = $this->buildConfigEntry($key, $value);
                    }
                }
            }
        } else {
            foreach ($config_file as $key => $value) {
                $entries[] = $this->buildConfigEntry($key, $value);
            }
        }

        foreach ($entries as $entry) {
            Cli::output($entry);
        }
    }

    private function buildConfigEntry($key, $value)
    {
        $key = str_pad(Color::blue($key), 20) . ":\t";
        return $key . $this->value($value);
    }

    private function value($var)
    {
        switch (gettype($var)) {
            case 'integer':
            case 'double':
                return $var;
                break;
            case 'string':
                return "'$var'";
                break;
            case 'boolean':
                return $var ? 'true' : 'false';
                break;
            default:
                return 'Invalid';
                break;
        }
    }

    public function saveConfig()
    {
        try {
            $this->parser->saveGeneratedConfig();
        } catch (FatalException $e) {
            Cli::fatal($e->getMessage());
        }
    }

    public function help()
    {
        print <<<EOT
    Usage:    yamw config [command] [options]

        Available commands:
            add [key] [value]
            set [key] [value]
            get [key] or get all
            remove [key]
            list [--only <wildcards>]

        Available Options:
            Erm... try it again at a later time please
EOT;
    }
}
