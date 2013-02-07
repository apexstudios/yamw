<?php
namespace Yamw\Lib\ResourceManagement;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 */
class ResCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yamw\Lib\ResourceManagement\ResCache::retrieve
     */
    public function testRetrieve()
    {
        $test_string = rand_string();

        $hash = ResCache::save($test_string, 'css');

        $obj = ResCache::retrieve($hash);

        self::assertEquals($hash, (string)$obj['_id']);
        self::assertArrayHasKey('content', $obj);

        self::assertEquals($test_string, $obj['content']);
    }

    /**
     * @covers Yamw\Lib\ResourceManagement\ResCache::save
     */
    public function testSave()
    {
        $test_string = rand_string();
        $test_hash = sha1($test_string);
        $time = time();

        $hash = ResCache::save($test_string, 'css');
        self::assertEquals($test_hash, $hash);

        // Yes, we're doing a manual search here
        $obj = \Yamw\Lib\Mongo\AdvMongo::getConn()->yamw_resmgr->findOne(
            array('_id' => $hash)
        );

        // Check that it got saved right
        self::assertEquals($hash, (string)$obj['_id']);
        self::assertArrayHasKey('content', $obj);

        self::assertEquals($test_string, $obj['content']);
    }

    public function testCantSaveMultiple()
    {
        $test_string = rand_string();
        $test_hash = sha1($test_string);
        $time = time();

        // Save multiple times, make sure we get the same hash
        for ($i = 0; $i < 10; $i++) {
            $hash = ResCache::save($test_string, 'css');
            self::assertEquals($test_hash, $hash);
        }

        // Yes, we're doing a manual search here
        // And make sure we get all of them
        $count = \Yamw\Lib\Mongo\AdvMongo::getConn()->yamw_resmgr->find(
            array('_id' => $hash)
        )->count();
        self::assertSame(1, $count);
    }

    /**
     * @covers Yamw\Lib\ResourceManagement\ResCache::touch
     */
    public function testTouch()
    {
        $test_string = rand_string();
        $time = time();

        $hash = ResCache::save($test_string, 'css');

        sleep(3);

        ResCache::touch($hash);

        $obj = ResCache::retrieve($hash);

        self::assertEquals($time + 3, $obj['timestamp']->sec);

        // Assert that the content is still the same
        self::assertEquals($test_string, $obj['content']);
    }
}
