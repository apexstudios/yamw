<?php
namespace Yamw\Lib\Commandeer;

/**
 * Description of CommandeerTest
 *
 * @author AnhNhan
 */
class CommandeerTest extends \PHPUnit_Framework_TestCase
{
    public function testCmdInOut()
    {
        $command = "php";

        $test_string = "Hello World!";

        $input = "<?php echo '$test_string'; ?>";

        self::assertEquals($test_string, Commandeer::cmdInOut($command, $input, $return_value));

        self::assertEquals(0, $return_value);
    }
}
