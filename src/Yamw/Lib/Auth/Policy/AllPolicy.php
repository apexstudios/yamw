<?php
namespace Yamw\Lib\Auth\Policy;

use \Yamw\Lib\Assertions\BasicAssertions;

/**
 * Description of AllPolicy
 *
 * @author AnhNhan <anhnhan@outlook.com>
 */
class AllPolicy implements \Yamw\Lib\Auth\Interfaces\PolicyInterface
{
    private $name = "AllPolicy";

    public function accepts(\Yamw\Lib\Auth\Interfaces\PolicyObject $object)
    {
        BasicAssertions::assertIsEnum(
            $this->getName(),
            $object->getPolicies(),
            "Object does not accept policy"
        );
    }

    public function getName()
    {
        return $this->name;
    }
}

?>
