<?php
use Yamw\Lib\Builders\Markup\AbstractMarkup;

/**
 * @covers \Yamw\Lib\Builders\MarkUp\AbstractMarkup<extended>
 * @author AnhNhan
 *
 */
class AbstractMarkupTest extends PHPUnit_Framework_TestCase
{
    protected $class = 'Markup';
    
    /**
     * @covers \Yamw\Lib\Builders\MarkUp\AbstractMarkup::__construct
     * @covers \Yamw\Lib\Builders\MarkUp\AbstractMarkup::getContent
     * @covers \Yamw\Lib\Builders\MarkUp\AbstractMarkup::getName
     * @dataProvider data_markup
     */
    public function testConstructor($name, $content)
    {
        if (is_object($content)) {
            $content->makeDirty();
        }
        
        $class = $this->class;
        $inst = new $class($name, $content);
        
        $this->assertSame($name, $inst->getName());
        $this->assertSame($content, $inst->getContent());

        $this->assertInstanceOf('\Yamw\Lib\Builders\MarkUp\AbstractMarkup', $inst);
        $this->assertInstanceOf('\Yamw\Lib\Builders\Interfaces\YamwMarkupInterface', $inst);
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @dataProvider data_invalid_wo_null
     */
    public function testInvalidArgumentConstructorContent($content)
    {
        $class = $this->class;
        new $class('somestring', $content);
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @dataProvider data_invalid
     * @dataProvider data_invalid_name
     */
    public function testInvalidArgumentConstructorName($name)
    {
        $class = $this->class;
        new $class($name);
    }
    
    public function data_invalid_name()
    {
        return array(array(''), array(new XmlTag('hi')));
    }
    
    /**
     * @covers \Yamw\Lib\Builders\MarkUp\AbstractMarkup::appendContent
     * @dataProvider data_append
     */
    public function testAppendContent($content, $new_content, $exp_content) {
        $class = '\\Yamw\\Lib\\Builders\\MarkUp\\XmlTag';

        $inst = new $class('somename', $content);
        
        $this->assertEquals($content, $inst->getContent().'');
        
        $inst->appendContent($new_content);
        
        $this->assertEquals($exp_content, $inst->getContent().'');
    }
    
    public function data_append() {
        $class = '\\Yamw\\Lib\\Builders\\MarkUp\\XmlTag';
        
        return array(
            array(
                'hello', 'world', "helloworld"
            ),
            array(
                new $class('h1'), 'ohno', "    <h1 />\nohno"
            ),
            array(
                'o', new $class('h1'), "o    <h1 />\n"
            ),
            array(
                new $class('lol'), new $class('cat'), "    <lol />\n    <cat />\n"
            ),
        );
    }

    /**
     * @covers \Yamw\Lib\Builders\MarkUp\AbstractMarkup::removeContent
     * @dataProvider data_markup
     */
    public function testRemoveContent($content)
    {
        $class = $this->class;
        $inst = new $class('somestring', $content);
        $this->assertEmpty($inst->removeContent()->getContent());
    }

    /**
     * @expectedException InvalidArgumentException
     * @dataProvider data_invalid
     */
    public function testInvalidArgumentAppendContent($content)
    {
        $class = $this->class;
        $inst = new $class('somestring', $content);
        $inst->appendContent($content);
    }
    
    /**
     * @dataProvider data_method_chain
     */
    public function testMethodChain($methodname, $arg, $arg2 = null)
    {
        $class = $this->class;
        $inst = new $class('somestring', $arg);
        
        if($arg && $arg2) {
            $r = $inst->$methodname($arg, $arg2);
        } elseif($arg) {
            $r = $inst->$methodname($arg);
        } else {
            $r = $inst->$methodname();
        }
        
        $this->assertInstanceOf(get_parent_class($inst), $r);
    }
    
    public function data_invalid_wo_null()
    {
        return array(
            array(new OtherClass())
        );
    }
    
    public function data_invalid()
    {
        $vars = array(
            array(null),
            array(
                array(1, 2, 3)
            )
        );
        return array_merge($this->data_invalid_wo_null(), $vars);
    }
    
    public function data_markup()
    {
        $class = $this->class;
        return array(
            array('namw', 'hello'),
            array('dfzb', true),
            array('fj', false),
            array('hr', null),
            array('fth', 0),
            array('d', 15),
            array('dhtr', 3.14),
            array('htf', new $class('somestring', 'somecontent'))
        );
    }
    
    public function data_method_chain()
    {
        return array(
            array('setContent', 'somestring'),
            array('appendContent', 'somestring'),
            array('removeContent', null)
        );
    }
}

// Stubs
class OtherClass
{
    
}

class Markup extends AbstractMarkup
{
    public function __toString()
    {
        return "";
    }
}
