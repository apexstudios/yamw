<?php
namespace Yamw\Models;

class SpaceUnit extends IModel
{
    protected $table = 'units_space';
    private $aff;
    private $layer;
    
    public function getName()
    {
        return $this->Get('name');
    }
    
    public function getDescription()
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
            return BBCode2HTML($this->Get('description'));
        }
    }
    
    public function getRawDesc()
    {
        return htmlentities($this->Get('description'));
    }
    
    public function getImage($html = true)
    {
        if ($this->Get('image') && $html) {
            return TemplateHelper::img_for($this->Get('image'), '', false);
        } else {
            return $this->Get('image');
        }
    }
    
    public function getAffiliation()
    {
        if (!isset($this->aff)) {
            switch ($this->Get('affiliation')) {
                default:
                    $t = 'Unknown';
                    break;
                case 1:
                    $t = 'Covenant';
                    break;
                case 2:
                    $t = 'UNSC';
                    break;
                case 3:
                    $t = 'Flood';
                    break;
                case 4:
                    $t = 'Forerunners';
                    break;
                case 5:
                    $t = 'United Rebel Front';
                    break;
            }
            $this->aff = $t;
        }
        return $this->aff;
    }
    
    public function getAffiliationId()
    {
        return $this->Get('affiliation');
    }
    
    public function getLayer()
    {
        if (!isset($this->layer)) {
            switch($this->Get('layer')) {
                default:
                    $t = 'Unknown';
                    break;
                case 0:
                    $t = 'Fighter';
                    break;
                case 1:
                    $t = 'Bomber';
                    break;
                case 2:
                    $t = 'Corvette';
                    break;
                case 3:
                    $t = 'Frigate';
                    break;
                case 4:
                    $t = 'Destroyer';
                    break;
                case 5:
                    $t = 'Cruiser';
                    break;
                case 6:
                    $t = 'Heavy Cruiser';
                    break;
                case 7:
                    $t = 'Carrier';
                    break;
            }
            $this->layer = $t;
        }
        return $this->layer;
    }
    
    public function getLayerId()
    {
        return $this->Get('layer');
    }
}
