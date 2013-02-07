<?php
namespace Yamw\Modules;

class RootController
{
    protected $module;
    protected $action;
    protected $section;

    public function __construct($module, $action, $section)
    {
        $this->module = $module;
        $this->action = $action;
        $this->section = $section;
    }
}
