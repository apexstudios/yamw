<?php
namespace Yamw\Lib\Analytical;

/**
 * A class generating analytics
 *
 * @author AnhNhan
 * @package Yamw
 * @subpackage Analytics
 */
class Analytical
{
    /**
     *
     * @param type $name
     * @param array $specifics
     *
     * @return \Yamw\Lib\Analytical\Analytics\AbstractAnalytics
     */
    public function getAnalytic($name, array $specifics = null)
    {
        $className = "Yamw\\Lib\\Analytical\\Analytics\\" . functionify($name) . "Analytics";
        return new $className($specifics);
    }
}
