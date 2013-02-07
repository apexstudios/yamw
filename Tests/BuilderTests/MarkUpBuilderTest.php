<?php
use Yamw\Lib\Builders\Markup\HtmlTag;
use Yamw\Lib\Builders\Markup\XmlTag;
use Yamw\Lib\Builders\MarkupBuilder;
use Yamw\Lib\Builders\Interfaces\YamwMarkup;

class MarkUpBuilderTest extends PHPUnit_Framework_TestCase
{
    protected $class = '\\Yamw\\Lib\\Builders\\MarkupBuilder';
    
    public function testInstanceType()
    {
        $this->assertInstanceOf($this->class, new MarkupBuilder());
    }
    
    public function testSimpleBuild()
    {
        $builder = new MarkupBuilder();
        $builder->addMarkUp(new XmlTag('a', 'This is a link', array('href' => 'http://megatokyo.com/')));
        $builder->addMarkUp(new HtmlTag('span', 'Or rather don\'t'));
        $this->assertRegExp("/<a href=\".*?\">.*?<\/a>\n.*?<span>.*?<\/span>/", $builder->retrieve());
    }
}
