<?php
namespace Yamw\Models;

class MenuEntry extends IModel
{
    protected $table = 'menu_entries';
    protected $id_column = 'meid';

    public function getName()
    {
        return $this->Get('name');
    }

    public function getLabel()
    {
        return $this->Get('label');
    }

    public function getLink($raw = false)
    {
        if ($raw) {
            return $this->Get('link');
        } else {
            return '<a href="'.$this->Get('link').'" id="menu-'.$this->getName()
                .'" '.($this->getTarget() ? 'target="'.$this->getTarget().'" ' : '')
                .'><span>'.$this->getLabel().'</span></a>';
        }
    }

    public function getTarget()
    {
        return $this->Get('target');
    }
}
