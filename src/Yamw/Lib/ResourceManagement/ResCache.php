<?php
namespace Yamw\Lib\ResourceManagement;

use Yamw\Lib\Assertions\BasicAssertions;

/**
 * Handles the caching of the resources
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage ResourceManagement
 */
class ResCache
{
    const COLLECTION = "yamw_resmgr";

    /**
     * Retrieves a resource
     *
     * @param string $id
     * The sha1 hash used to identify the resource
     *
     * @return array|null
     * The resource
     *
     * <pre>array
     *   |- _id        - sha1-hash
     *   |- timestamp  - MongoDate
     *   |- content    - saved content
     *
     * null if it does not exist
     */
    public static function retrieve($id)
    {
        BasicAssertions::assertIsString($id);

        $query = self::query()->findOne(
            array('_id' => $id)
        );

        return $query;
    }

    /**
     * Attempts to save a resource in the database
     *
     * If the resource already exists, it will instead be touched upon
     * @see ResCache::touch
     *
     * @param string $content
     * The resouce you want to save
     *
     * @return string
     * The sha1 hash of the resource, which is used to identify it in DB
     */
    public static function save($content/*, $type*/)
    {
        BasicAssertions::assertIsString($content);
        BasicAssertions::assertIsFilledString($content);

        $hash = sha1($content);

        // Does the resource already exist in the database?
        $count = self::query()->find(array('_id' => $hash))
            ->count();

        if (!$count) {
            self::query()->insert(
                array(
                    '_id' => $hash,
                    'timestamp' => new \MongoDate(),
                    'content' => $content
                )
            );
        } else {
            // If it does already exist, just touch it
            self::touch($hash);
        }

        return $hash;
    }

    /**
     * Updates the timestamp for a given
     *
     * @param type $id
     * @return type
     */
    public static function touch($id)
    {
        BasicAssertions::assertIsString($id);

        return self::query()->update(
            array('_id' => $id),
            array('$set' => array('timestamp' => new \MongoDate()))
        );
    }

    /**
     * Returns the MongoCollection for query
     *
     * @return MongoCollection
     */
    private static function query()
    {
        return \Yamw\Lib\Mongo\AdvMongo::getConn()
            ->selectCollection(self::COLLECTION);
    }
}
