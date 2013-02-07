<?php
namespace Yamw\Views\Interfaces;

/**
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Views
 */
interface ViewInterface {
    /**
     * Adds an element to the vast array of... this view.
     *
     * @param string $name
     * The name of the slot this element should belong to.
     *
     * @param object $element
     * The element itself
     */
    public function addElement($name, $element);

    /**
     * Returns a serially rendered representation of all the data you put into
     * it. Usually, this is HTML.
     */
    public function __toString();
}
