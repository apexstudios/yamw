<?php
namespace Yamw\Lib;

use Yamw\Lib\Mongo\AdvMongo;

class Tasks
{
    const PENDING = 100;
    const PROCESSING = 150;
    const FINISH = 1337;

    const MONGO_COLLECTION = 'yamw_tasks';

    public static function addTask($name, array $opts)
    {
        if (!is_string($name) || !strlen($name)) {
            throw new \InvalidArgumentException();
        }

        // TODO: Check if not already exists

        return AdvMongo::getConn()->selectCollection(self::MONGO_COLLECTION);
    }

    public static function removeTask($name)
    {
        if (is_array($name)) {
            $r = array();

            foreach ($name as $k => $v) {
                $res = AdvMongo::getConn()->selectCollection(self::MONGO_COLLECTION)
                    ->remove(array('_id' => $v), array('w' => 1));
                $r[$v] = $res['ok'];
            }

            return $r;
        } else {
            $res = AdvMongo::getConn()->selectCollection(self::MONGO_COLLECTION)
                ->remove(array('_id' => $name), array('w' => 1));
            return $res['ok'];
        }
    }

    public static function processTask()
    {
        return 'stub';
    }

    public static function markTask()
    {
        return 'stub';
    }
}
