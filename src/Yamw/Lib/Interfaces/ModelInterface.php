<?php
namespace Yamw\Lib\Interfaces;

interface ModelInterface
{
    /**
     * @deprecated
     */
    public function getData();
    /**
     * @deprecated
     * @param array $val
     */
    public function setData(array $val);

    /**
     * The table/collection this entry is contained in
     * @return string The name of the table/collection
     */
    public function getTable();

    /**
     * The Id of the current value
     * @return mixed The Id
     */
    public function getId();
    //     /**
    //      * Changes the Id value of this entry
    //      * @param mixed $val the new value of the id
    //      */
    //     public function setId($val);

    /**
     * Saves the entry back into the DB
     */
    public function save();
}
