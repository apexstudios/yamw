<?php
namespace Yamw\Models;

class Update extends IModel
{
    protected $table = 'updates';

    public function getDate()
    {
        return $this->Get('date');
    }
    
    public function getDateText()
    {
        return 'Apex Studios Update - '.$this->getDate().'';
    }

    public function getText()
    {
        useHelper('BBCode');
        if (USE_CACHE) {
            $ret = $this->Get('cached_text');
            if (!$ret) {
                $this->BBCodeCache('text');
                return $this->Get('cached_text');
            } else {
                return $ret;
            }
        } else {
            $this->BBCodeCache('text');
            return $this->Get('cached_text');
        }
    }

    public function getRawText()
    {
        return $this->Get('text');
    }

    public function getDescription()
    {
        return BBCode2HTML($this->Get('desc'));
    }
}
