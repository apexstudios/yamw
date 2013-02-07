<?php
namespace Yamw\Lib\Interfaces;

interface ModelizerInterface extends ModelInterface
{
    public function update();
    
    /**
     * Removes the current entry from the DB
     */
    public function remove();
    
    /**
     * Tells the entry to persist and not stay temporary
     */
    public function persist();
    public function save();
}
