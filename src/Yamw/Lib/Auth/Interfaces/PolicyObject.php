<?php
namespace Yamw\Lib\Auth\Interfaces;

/**
 * An interface for objects that would like to implement Auth in conjunction
 * with Policy. This would for example enable objects like
 * `SpecialBlogViewController` to be displayed for only certain users. To
 * implement such a feature, it would implement `PolicyObject` (not
 * `PolicyInterface`).
 * 
 * The ViewController for example could be visible to Guests and Registered
 * Users, but not towards staff and admins (who are technically registered
 * users, too).
 * 
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Auth
 */
interface PolicyObject
{
    public function getPolicies();
}