<?php
/**
 * The Builder interface
 *
 * @author AnhNhan
 * @package Yamw\Lib\Builders
 */

namespace Yamw\Lib\Builders\Interfaces;

/**
 * Builderinterface
 *
 * @author AnhNhan
 *
 */
interface BuilderInterface
{
    /**
     * Tells the builder to build its mark-up
     */
    public function build();
    
    public function isBuilt();
    
    /**
     * Returns the built mark-up
     *
     * @return string The generated mark-up
     */
    public function retrieve();
}
