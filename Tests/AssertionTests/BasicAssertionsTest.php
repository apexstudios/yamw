<?php
use Yamw\Lib\Assertions\BasicAssertions;

class BasicAssertionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 10
     */
    public function testAssertNonStringFails()
    {
        BasicAssertions::assertIsString(123);
    }

    public function testAssertStringSucceeds()
    {
        try {
            // Yes, PHP behaviour
            BasicAssertions::assertIsString(
                'p'
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 20
     */
    public function testAssertNonNumericFails()
    {
        BasicAssertions::assertIsNumeric('somestring');
    }

    public function testAssertNumericSucceeds()
    {
        try {
            // Yes, PHP behaviour
            BasicAssertions::assertIsNumeric(
                '3.14'
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 30
     */
    public function testAssertNonArrayFails()
    {
        BasicAssertions::assertIsArray('somestring');
    }

    public function testAssertArraySucceeds()
    {
        try {
            // Yes, PHP behaviour
            BasicAssertions::assertIsArray(
                array()
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 40
     */
    public function testAssertNonNumberFails()
    {
        BasicAssertions::assertIsNumber('40');
    }

    public function testAssertNumberSucceeds()
    {
        try {
            BasicAssertions::assertIsNumber(
                3.14
            );
            BasicAssertions::assertIsNumber(
                3
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 50
     */
    public function testAssertNonIntegerFails()
    {
        BasicAssertions::assertIsInt('3');
    }

    public function testAssertIntegerSucceeds()
    {
        try {
            BasicAssertions::assertIsInt(
                3
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 60
     */
    public function testAssertNonFloatFails()
    {
        BasicAssertions::assertIsFloat('3.14');
    }

    public function testAssertFloatSucceeds()
    {
        try {
            BasicAssertions::assertIsFloat(
                3.14
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 90
     */
    public function testAssertNonObjectFails()
    {
        BasicAssertions::assertIsObject('somestring');
    }

    public function testAssertObjectSucceeds()
    {
        try {
            BasicAssertions::assertIsObject(
                $this
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 70
     */
    public function testAssertNotEmptyFails()
    {
        BasicAssertions::assertIsEmpty('somestring');
    }

    public function testAssertEmptySucceeds()
    {
        try {
            BasicAssertions::assertIsEmpty(
                ''
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 80
     */
    public function testAssertNotSetFails()
    {
        BasicAssertions::assertIsSet(null);
    }

    public function testAssertSetSucceeds()
    {
        try {
            BasicAssertions::assertIsSet(
                'p'
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 35
     */
    public function testAssertEmptyArrayFails()
    {
        BasicAssertions::assertIsFilledArray(array());
    }

    public function testAssertFilledArraySucceeds()
    {
        try {
            BasicAssertions::assertIsFilledArray(
                array('')
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 15
     */
    public function testAssertEmptyStringFails()
    {
        BasicAssertions::assertIsFilledString('');
    }

    public function testAssertNonemptyStringSucceeds()
    {
        try {
            BasicAssertions::assertIsFilledString(
                'p'
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 110
     */
    public function testAssertNonEnumFails()
    {
        BasicAssertions::assertIsEnum('hello', array('hi', 'hey'));
    }

    public function testAssertEnumSucceeds()
    {
        try {
            BasicAssertions::assertIsEnum(
                'p',
                array('a', 'b', 'p')
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 100
     */
    public function testAssertNonTypeFails()
    {
        BasicAssertions::assertIsTypeOf(
            $this,
            '\\Yamw\\Lib\\Assertions\\BasicAssertions'
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 90
     */
    public function testAssertNonTypeFails2()
    {
        BasicAssertions::assertIsTypeOf(
            'somestring',
            '\\Yamw\\Lib\\Assertions\\BasicAssertions',
            "'somestring' is apparently an object... Interesting."
        );
    }

    public function testAssertTypeSucceeds()
    {
        try {
            BasicAssertions::assertIsTypeOf(
                $this,
                __CLASS__
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 120
     */
    public function testAssertNonEqualFails()
    {
        BasicAssertions::assertIsEqual(5, 'somestring');
    }

    public function testAssertEqualSucceeds()
    {
        try {
            // Yes, PHP behaviour
            BasicAssertions::assertIsEqual(
                'p',
                0
            );

            BasicAssertions::assertIsEqual(1, '1');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 130
     */
    public function testAssertNotSameFails()
    {
        BasicAssertions::assertIsSame('somestring', 'someotherstring');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 130
     */
    public function testAssertNotSameFails2()
    {
        BasicAssertions::assertIsSame(0, 'somestring');
    }

    public function testAssertSameSucceeds()
    {
        try {
            BasicAssertions::assertIsSame(
                'p',
                "p"
            );
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }
}
