<?php
namespace Yamw\Lib\Builders\Interfaces;

interface YamwMarkupInterface
{
    public function isPretty();
    public function makePretty();
    public function makeDirty();
    
    /**
     * Generates and returns the string representation of this markup
     *
     * @return string
     */
    public function __toString();
}
