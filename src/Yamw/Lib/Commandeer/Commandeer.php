<?php
namespace Yamw\Lib\Commandeer;

/**
 * Class for handling CMD stuff
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Commandeer
 */
class Commandeer
{
    /**
     * Executes a command, inserts data by piping it, and returns the output
     *
     * @param string $command
     * The command to be executed
     *
     * @param mixed $input
     * The input you want to pipe/stdin into the command
     *
     * @param int &$return_value
     * $return_value is set to the exit value of the executed command
     *
     * @param string &$error
     * $error is set to STDERR so you know what went wrong
     *
     * @return mixed
     * The output the command directed to stdout
     *
     * @throws \Exception
     */
    public static function cmdInOut($command, $input, &$return_value = null, &$error = null)
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w"),
        );

        $pointer = proc_open($command, $descriptorspec, $pipes, path());

        if (!is_resource($pointer)) {
            throw new \Exception("Could not open command!");
        }

        fwrite($pipes[0], $input);
        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        // It is important that you close any pipes before calling
        // proc_close in order to avoid a deadlock
        $return_value = proc_close($pointer);

        return $output;
    }
}
