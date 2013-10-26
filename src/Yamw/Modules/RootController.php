<?php
namespace Yamw\Modules;

class RootController
{
    protected $module;
    protected $action;
    protected $section;

    private $request;

    public function __construct($module, $action, $section, \Yamw\Lib\Request $request = null)
    {
        $this->module = $module;
        $this->action = $action;
        $this->section = $section;
        $this->request = $request;
    }

    protected function getRequest()
    {
        return $this->request;
    }
}
