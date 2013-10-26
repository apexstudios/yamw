<?php
namespace Yamw\Lib\Mongo;

class Gallery
{
    public static function getImage($filename)
    {
        return AdvMongo::GridFs('gallery')->findOne(array('_id' => new \MongoId($filename)));
    }

    public static function getSection($section = "all")
    {
        // TMP
        $query = array(
            'metadata.section' => new \MongoRegex("/$section|all/")
        );

        return AdvMongo::gridFs('gallery')->find(
            $query
        )->sort(array('uploadDate' => -1));




        if (strncmp('hub', $section, 3)) {
            $query = array(
                '$or' => array(
                    array('metadata.plus_hub' => true),
                    array('metadata.section' => 'all'),
                )
            );
        } else {
            $query = array(
                'metadata.section' => new \MongoRegex("/$section|all/")
            );
        }

        return AdvMongo::gridFs('gallery')->find(
            $query
        )->sort(array('uploadDate' => -1));
    }

    public static function getThumbnail($filename, $size)
    {
        $q = array('metadata.thumb_of' => $filename, 'metadata.size' => $size);

        // Update the download counter
        AdvMongo::gridFs('thumbs')
            ->update($q, array('$inc' => array('metadata.downloads' => 1)), array('w' => 0));

        return AdvMongo::gridFs('thumbs')->findOne($q);
    }

    public static function deleteThumbnail($filename, $size)
    {
        return AdvMongo::gridFs('thumbs')->remove(
            array(
                'metadata.thumb_of' => $filename,
                'metadata.size' => $size
            ),
            array('w' => 1)
        );
    }

    public static function deleteAllThumbnails($filename = null)
    {
        if ($filename === null) {
            return;
        }

        return AdvMongo::gridFs('thumbs')->remove(
            $filename ? array('metadata.thumb_of' => $filename) : array(),
            array('w' => 1)
        );
    }

    public static function storeThumbnail($filename, $size, $bytes, $ext = null)
    {
        if ($ext === null) {
            $file_ext = explode('.', $filename);
            $ext = $file_ext[count($file_ext) - 1];
        }

        return AdvMongo::gridFs('thumbs')->storeBytes(
            $bytes,
            array('filename' => $filename.'-'.$size,
            'metadata' => array(
            'type' => $ext,
            'thumb_of' => $filename,
            'size' => $size,
            'created_on' => new \MongoDate(),
            'downloads' => 0
            ))
        );
    }
}
