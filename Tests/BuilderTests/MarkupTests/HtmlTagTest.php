<?php
use \Yamw\Lib\Builders\Markup\HtmlTag;

require_once __DIR__.'/XmlTagTest.php';

class HtmlTagTest extends XmlTagTest
{
    protected $class = '\\Yamw\\Lib\\Builders\\Markup\\HtmlTag';

    public function testParent() {
        $inst = new HtmlTag('name');
        $this->assertSame('Yamw\\Lib\\Builders\\Markup\\XmlTag', get_parent_class($inst));
    }
}
