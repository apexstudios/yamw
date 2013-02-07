<?php
use Yamw\Lib\Mongo\AdvMongo;

$indexes = array(
    'yamw_stats' => array(
        array(
            'indexes' => array('page' => 1, 'time' => -1),
            'options' => array()
        )
    ),
    'gallery.files' => array(
        array(
            'indexes' => array('filename' => 1),
            'options' => array('unique' => 1)
        ),
        array(
            'indexes' => array('metadata.downloads' => -1),
            'options' => array()
        ),
    ),
    'media.files' => array(
        array(
            'indexes' => array('filename' => 1),
            'options' => array('unique' => 1)
        ),
        array(
            'indexes' => array('metadata.downloads' => -1),
            'options' => array()
        ),
    ),
    'audio.files' => array(
        array(
            'indexes' => array('filename' => 1),
            'options' => array('unique' => 1)
        ),
        array(
            'indexes' => array('metadata.downloads' => -1),
            'options' => array()
        ),
    ),
    'other.files' => array(
        array(
            'indexes' => array('filename' => 1),
            'options' => array('unique' => 1)
        ),
        array(
            'indexes' => array('metadata.downloads' => -1),
            'options' => array()
        ),
    ),
    'thumbs.files' => array(
        array(
            'indexes' => array('filename' => 1),
            'options' => array('unique' => 1)
        ),
        array(
            'indexes' => array('metadata.downloads' => -1),
            'options' => array()
        ),
    )
);

foreach ($indexes as $col => $params) {
    foreach ($params as $value) {
        AdvMongo::getConn()->selectCollection($col)->ensureIndex($value['indexes'], $value['options']);
    }
}

