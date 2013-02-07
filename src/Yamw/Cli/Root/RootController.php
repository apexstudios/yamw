<?php
namespace Yamw\Cli\Root;

use Yamw\Cli\Interfaces\Help;
use Yamw\Lib\Config;

class RootController
{
    protected $nonopts;
    protected $opts;

    protected $action;

    public function __construct(array $nonopts = null, array $opts = null)
    {
        if (is_array($nonopts)) {
            $this->nonopts = $nonopts;
        } else {
            $this->nonopts = array();
        }

        if (is_array($opts)) {
            $this->opts = $opts;
        } else {
            $this->opts = array();
        }

        $this->action = isset($nonopts[2]) ? $nonopts[2] : null;
    }

    // Dummy to load help at default
    public function mainAction()
    {
        $this->helpAction();
    }

    public function helpAction()
    {
        if ($this instanceof Help) {
            $this->help();
        }
    }

    /**
     * Returns the cli option value
     *
     * @param string $name
     *
     * @return boolean|null
     */
    public function getOpt($name)
    {
        if (isset($this->opts[$name])) {
            switch ($name)
            {
                case 'color':
                case 'cli':
                case 'action':
                case 'only':
                    // Toggles
                    return true;
                    break;
                default:
                    // Values
                    return $this->opts[$name];
                    break;
            }
        } else {
            return null;
        }
    }
}
