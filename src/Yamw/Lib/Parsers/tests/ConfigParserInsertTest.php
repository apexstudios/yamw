<?php
namespace Yamw\Lib\Parsers;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 */
class ConfigParserInsertTest extends \PHPUnit_Framework_TestCase
{
    private static $configfile;
    private static $configpath = 'Yamw\Lib\Parsers\tests\mocks\insert_init.php';

    private $parser;

    public static function setUpBeforeClass()
    {
        self::$configfile = file_get_contents(path(self::$configpath));
    }

    public function setUp()
    {
        $this->parser = new ConfigParser(self::$configpath);
    }

    public function tearDown()
    {
        // Not required yet, since we do not save anything
        // file_put_contents(path(self::$configpath), self::$configfile);
    }

    public function testPrependEntryWhenLogicallyFirst()
    {
        $this->parser->addEntry('aanh', 'the avatar');

        $retVal = eval($this->parser->getGeneratedConfig());

        self::assertCount(5, $retVal, "Entry was apparently not inserted...");
        self::assertArrayHasKey("aanh", $retVal);
        self::assertSame(
            'the avatar',
            array_shift($retVal),
            "Inserted entry not first!"
        );
    }

    public function testAppendEntryWhenLogicallyLast()
    {
        $this->parser->addEntry('zzz', 'sleeping');

        $retVal = eval($this->parser->getGeneratedConfig());

        self::assertCount(5, $retVal, "Entry was apparently not inserted...");
        self::assertArrayHasKey("zzz", $retVal);
        self::assertSame(
            'sleeping',
            array_pop($retVal),
            "Inserted entry is not last"
        );
    }

    public function testInsertEntryLogically()
    {
        $this->parser->addEntry('nada', 'nothing');

        $retVal = eval($this->parser->getGeneratedConfig());

        self::assertCount(5, $retVal, "Entry was apparently not inserted...");
        self::assertArrayHasKey("nada", $retVal);

        array_shift($retVal);
        array_shift($retVal);

        self::assertSame(
            'nothing',
            array_shift($retVal),
            "Inserted entry not somewhere inbetween"
        );
    }
}
