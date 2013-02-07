<?php
class AnalyticsTest extends PHPUnit_Framework_TestCase
{
    private static $processed;

    public function testHasName()
    {
        $specific = '/caw/update/show/21';

        $analytical = new Yamw\Lib\Analytical\Analytical;
        $analytic = $analytical->getAnalytic(
            'performance distribution',
            array($specific)
        );

        self::assertEquals('Performance Distribution', $analytic->getName());
    }

    public function testTargetedAnalytics()
    {
        $specific = '/caw/update/show/21';

        $analytical = new Yamw\Lib\Analytical\Analytical;
        $analytic = $analytical->getAnalytic(
            'performance distribution',
            array($specific)
        );

        self::$processed = $analytic->getProcessed();

        // Assert that this is the only result - means $specific worked
        self::assertCount(1, self::$processed);

        $target = & self::$processed[$specific];

        // Assert that we have the three attributes
        self::assertCount(3, $target);
        self::assertArrayHasKey('max_memory', $target);
        self::assertArrayHasKey('numqueries', $target);
        self::assertArrayHasKey('pagetime', $target);

        // Assert that each of them contains at least one value
        foreach ($target as $value) {
            self::assertGreaterThanOrEqual(1, count($value));
        }
    }

    public function testMultipleAnalytics()
    {
        // IGnore setup

        $specific = array('/caw/update/show/21', '/', '/admin');

        $analytical = new Yamw\Lib\Analytical\Analytical;
        $analytic = $analytical->getAnalytic(
            'performance distribution',
            $specific
        );

        $processed = $analytic->getProcessed();
        self::assertCount(3, $processed);

        foreach ($processed as $value) {
            // Assert that we have the three attributes
            self::assertCount(3, $value);
            self::assertArrayHasKey('max_memory', $value);
            self::assertArrayHasKey('numqueries', $value);
            self::assertArrayHasKey('pagetime', $value);
        }
    }
}
