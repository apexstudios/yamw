<?php
class GlobalTest extends PHPUnit_Framework_TestCase
{
    public function testLogger()
    {
        $logger = getLogger();
        self::assertInstanceOf('\Monolog\Logger', $logger);
        self::assertEquals('def', $logger->getName());

        $logger = getLogger('name');
        self::assertInstanceOf('\Monolog\Logger', $logger);
        self::assertEquals('name', $logger->getName());
    }

    /**
     * @dataProvider data_slug
     */
    public function testSlug($text, $exp)
    {
        self::assertEquals($exp, slugify($text));
    }

    public function data_slug()
    {
        return array(
            array('this is a slug', 'this-is-a-slug'),
            array(' sse  gesr gSe+% rdgj', 'sse-gesr-gse-rdgj'),
            array('this is a slug', 'this-is-a-slug'),
            array('this is a slug', 'this-is-a-slug'),
        );
    }

    /**
     * @dataProvider data_functionify
     */
    public function testFunctionify($string, $exp)
    {
        self::assertEquals($exp, functionify($string));
    }

    public function data_functionify()
    {
        return array(
            array('this is a slug', 'ThisIsASlug'),
        );
    }

    /**
     * @dataProvider data_time_label
     */
    public function testTimeLabel($timestamp, $expString)
    {
        // We're using 0 as $now, simplifies testing
        self::assertEquals($expString, getTimeLabel(0 - $timestamp, 0));
    }

    public function data_time_label()
    {
        return array(
            array(
                90, 'Just right now'
            ),
            array(
                130, '2 minutes before'
            ),
            array(
                35 * 60, '35 minutes before'
            ),
            array(
                2 * 60 * 60, '2 hours and 0 minutes before'
            ),
            array(
                3.5 * 60 * 60, '3 hours and 30 minutes before'
            ),
            array(
                5 * 60 * 60, '5 hours before'
            ),
            array(
                17.5 * 60 * 60, '17 hours before'
            ),
            array(
                25 * 60 * 60, '25 hours before'
            ),
            array(
                3.5 * 24 * 60 * 60, '3 days before'
            ),
            array(
                8 * 24 * 60 * 60, '1 weeks before'
            ),
            array( // An actual date! W00t!
                7 * 7 * 24 * 60 * 60, date(DATE_ANH_NHAN, -7 * 7 * 24 * 60 * 60)
            ),
        );
    }
}
