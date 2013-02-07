<?php
namespace Yamw\Lib\ResourceManagement\Builders;

/**
 * Builds your Css files
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage ResourceManagement
 */
class CssBuilder
{
    /**
     * Builds a CSS resource from a file
     *
     * @param string $path
     * The path to a CSS/LESS file
     *
     * @return type
     * The final compressed CSS
     */
    public static function buildFile($path)
    {
        $obj = new \lessc();
        $obj->setFormatter('compressed');

        try {
            $output = $obj->compileFile($path);
        } catch (\Exception $e) {
            throw $e;
        }

        return $output;
    }

    /**
     * Builds a CSS resource from string
     *
     * @param string $string
     *
     * @return type
     * The compiled and compressed CSS
     */
    public static function buildString($string)
    {
        $obj = new \lessc();
        $obj->setFormatter('compressed');

        try {
            $output = $obj->compile($string);
        } catch (\Exception $e) {
            throw $e;
        }

        return $output;
    }
}
