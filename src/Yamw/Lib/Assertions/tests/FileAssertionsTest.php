<?php
namespace Yamw\Lib\Assertions;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 */
class FileAssertionsTest extends \PHPUnit_Framework_TestCase
{
    public function testWorksOnExistingFiles()
    {
        // Stub
        $file = "Yamw/Lib/Assertions/tests/mocks/existing_file";
        $this->assertTrue(FileAssertions::assertFileExists($file));
    }

    /**
     * @expectedException Yamw\Lib\Assertions\Exceptions\FileNotFoundException
     */
    public function testTyposInPathHaveHorrendousEffects()
    {
        // Stub
        $file = "Yamw/Lib/Assertions/tests/mocks/ekusiszingu_fairu";
        FileAssertions::assertFileExists($file);
    }
}
