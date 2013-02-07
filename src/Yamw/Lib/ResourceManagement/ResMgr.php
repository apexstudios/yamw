<?php
namespace Yamw\Lib\ResourceManagement;

use Yamw\Lib\Assertions\BasicAssertions;

/**
 * The Resource Manager handles all static resources and compiles them into one
 * big resource, as well as provides the necessary links.
 *
 * Currently only Js and Css are supported
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage ResourceManagement
 */
class ResMgr
{
    public static function requestResource($id)
    {
        return ResCache::retrieve($id);
    }

    public static function compileAndSave($type)
    {
        return new ResCompiler($type);
    }
}
