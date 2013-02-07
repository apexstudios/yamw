<?php
namespace Yamw\Lib\Auth\Interfaces;

interface PolicyInterface
{
    /**
     * Tells whether an object can by handled by a policy.
     *
     * @param object $object
     *
     * @throws 'nuthing
     */
    public function accepts(PolicyObject $object);

    public function getName();
}