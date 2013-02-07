<?php
namespace Yamw\Lib\ResourceManagement\Builders;

use Yamw\Lib\Commandeer\Commandeer;

/**
 * Compiles your Js resources ^^
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage ResourceManagement
 */
class JsBuilder
{
    public static function buildFromString($text)
    {
        $command = "java -jar ../yuicomp.jar --type js";
        $return_value = null;
        $error = null;

        $compressed = Commandeer::cmdInOut(
            $command,
            $text,
            $return_value,
            $error
        );

        if ($return_value === 0) {
            return $compressed;
        } else {
            throw new \RuntimeException($error, $return_value);
        }
    }

    public static function buildFromFile($path)
    {
        return self::buildFromString(file_get_contents($path));
    }
}
