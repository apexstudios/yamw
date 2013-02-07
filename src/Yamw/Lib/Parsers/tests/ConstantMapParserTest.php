<?php
namespace Yamw\Lib\Parsers;

class ConstantMapParserTest extends \PHPUnit_Framework_TestCase
{
    private $filePath = 'mappath.php';
    private $path;

    /**
     * @var ConstantMapParser
     */
    private $parser;

    public function setUp()
    {
        $this->path = path($this->filePath);
        $this->parser = new ConstantMapParser($this->filePath);
    }

    public function tearDown()
    {
        if (file_exists(path($this->filePath))) {
            unlink(path($this->filePath));
        }
    }

    public function testCanSaveConstants()
    {
        $test_data = array(
            'stoppu' => 'nainu',
            'tomare' => 'ike',
            'ichi' => 'ni'
        );

        foreach ($test_data as $key => $value) {
            $this->parser->setEntry($key, $value);
        }

        $this->parser->save();

        $check = include $this->path;

        self::assertCount(count($test_data), $check);
        foreach ($test_data as $key => $value) {
            self::assertEquals($check[$key], $value);
        }
    }
}
