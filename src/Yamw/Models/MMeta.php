<?php
namespace Yamw\Models;

class MMeta extends IModel
{
    protected $table = 'meta';

    public function getName()
    {
        return $this->Get('name');
    }
    
    public function getTitle()
    {
        return $this->Get('title');
    }
    
    public function getDescription()
    {
        return $this->Get('description');
    }
    
    public function getShortContent()
    {
        return truncate_text($this->getRawContent(), 100);
    }

    public function getContent()
    {
        if (USE_CACHE) {
            $ret = $this->Get('cached_content');
            if (!$ret) {
                $this->BBCodeCache('content');
                return $this->Get('cached_content');
            } else {
                return $ret;
            }
        } else {
            $this->BBCodeCache('content');
            return $this->Get('cached_content');
        }
    }

    public function getRawContent()
    {
        return $this->Get('content');
    }
}
