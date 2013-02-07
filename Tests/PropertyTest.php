<?php
use Yamw\Lib\Property;

class PropertyTest extends PHPUnit_Framework_TestCase
{
    public $property;
    public static $init_val = 'someval';

    public function setUp()
    {
        $this->property = new Property(static::$init_val);
    }

    public function tearDown()
    {
        $this->property = null;
    }

    public function testIsNumeric()
    {
        $prop = new Property('string');
        self::assertFalse($prop->isNumeric());

        $prop = new Property('999');
        self::assertTrue($prop->isNumeric());

        $prop = new Property(3.14);
        self::assertTrue($prop->isNumeric());
    }

    public function testToStringWorksInContextOfStringConcatenation()
    {
        self::assertSame(static::$init_val, $this->property.'');
    }

    public function testToStringReturnsAString()
    {
        self::assertTrue(is_string($this->property->__toString()));
    }

    public function testPropertyKnowsItsSize()
    {
        self::assertEquals(strlen(static::$init_val), $this->property->size());
    }

    public function testPropertyKnowsWhetherItIsSet()
    {
        self::assertTrue($this->property->is_set());

        $new_property = new Property(null);
        self::assertFalse($new_property->is_set());
    }
}
