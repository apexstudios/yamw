<?php
namespace Yamw\Lib\ResourceManagement;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 */
class ResCompilerTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleCompile()
    {
        $files = array("css/less/main.less");

        $test_string = "";
        foreach ($files as $file) {
            $test_string .= file_get_contents(path($file));
        }

        $compiler = new ResCompiler("css");

        foreach ($files as $file) {
            $compiler->pushResource($file);
        }

        $hash = $compiler->compile();

        $obj = ResCache::retrieve($hash);

        self::assertEquals($hash, (string)$obj['_id']);
        self::assertArrayHasKey('content', $obj);

        self::assertGreaterThan(10000, strlen($obj['content']));

        // Run it through lessc ^^
        $command = "lessc - --yui-compress";

        \Yamw\Lib\Commandeer\Commandeer::cmdInOut($command, $obj['content'], $return_val);
        self::assertSame(0, $return_val);
    }
}
