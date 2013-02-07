<?php
use Yamw\Lib\Builders\Markup\MarkupContainer;
use Yamw\Lib\Builders\Markup\XmlTag;

class MarkupContainerTest extends PHPUnit_Framework_TestCase
{
    public function test123()
    {
        $container = new MarkupContainer(new XmlTag('heyho'));
        
        $container->getMarkupData();
        $container->pop();
        $container->push(new XmlTag('name'));
        $container->replace(array());
        $container->shift();
        $container->unshift(new XmlTag('name'));
        ''.$container;
        
        $this->assertTrue(true);
    }
}
