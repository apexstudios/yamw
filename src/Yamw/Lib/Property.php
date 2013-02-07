<?php
namespace Yamw\Lib;

class Property
{
    private $value;

    public function __construct($val)
    {
        if (!is_scalar($val) && !is_null($val)) {
            throw new \InvalidArgumentException('You tried to create a property with something weird!');
        }
        $this->value = $val;
    }

    /**
     * Whether this property is numeric
     * @return boolean
     */
    public function isNumeric()
    {
        return is_numeric($this->value);
    }

    /**
     * The length of the string representation
     *
     * @return number
     */
    public function size()
    {
        return strlen($this->value);
    }

    /**
     * Whether this property is a set value
     */
    public function is_set()
    {
        return isset($this->value);
    }

    /**
     * Returns the string representation of this property
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }
}
