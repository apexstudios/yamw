<?php
namespace Yamw\Models;

class About extends IModel
{
    protected $table = 'staff';

    public function getName()
    {
        return $this->Get('name');
    }

    public function getImage($html = true)
    {
        if ($html) {
            img_for($this->Get('img'), '', false);
        } else {
            return $this->Get('img');
        }
    }

    public function getPosition()
    {
        return $this->Get('position');
    }

    public function getDesc()
    {
        if (USE_CACHE) {
            $ret = $this->Get('cached_description');
            if (!$ret) {
                $this->BBCodeCache('description');
                return $this->Get('cached_description');
            } else {
                return $ret;
            }
        } else {
            $this->BBCodeCache('description');
            return $this->Get('cached_description');
        }
    }

    public function getRawDesc()
    {
        return $this->Get('description');
    }
}
