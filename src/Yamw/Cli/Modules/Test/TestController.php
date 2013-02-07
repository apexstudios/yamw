<?php
namespace Yamw\Cli\Modules\Test;

class TestController
{
    public function mainAction()
    {
        println("Yes, this is a test");
    }

    public function __call($name, $args)
    {
        $tmp = explode('::', $name);

        if (count($tmp) == 1) {

        } else {
            $class = $tmp[0];
            $method = $tmp[1];

            if (method_exists($class, $method)) {
                $class = new $class;
                echo $class->$method();
            }
        }
    }
}
