<?php
namespace Yamw\Lib\ResourceManagement\Builders;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 */
class CssBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBuildFromFileAndCompress()
    {
        $file = "/css/less/main.less";

        // We're only checking for compression here since checking for valid CSS
        // is kind of bothersome
        self::assertGreaterThan(
            13000,
            strlen(CssBuilder::buildFile(path($file))),
            null,
            4000
        );
    }
}
