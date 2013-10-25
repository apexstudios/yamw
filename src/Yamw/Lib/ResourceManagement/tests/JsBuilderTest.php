<?php
namespace Yamw\Lib\ResourceManagement\Builders;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 */
class JsBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleCompileFromFile()
    {
        $file = "Yamw/Lib/ResourceManagement/tests/mocks/test_js";
        $compare_file = "Yamw/Lib/ResourceManagement/tests/mocks/test_js_compressed";

        $result = JsBuilder::buildFromFile(path($file));
        self::assertEquals(file_get_contents(path($compare_file)), $result);
    }
}
