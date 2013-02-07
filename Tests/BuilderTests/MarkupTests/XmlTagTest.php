<?php
use Yamw\Lib\Builders\Markup\XmlTag;

/**
 * @covers \Yamw\Lib\Builders\MarkUp\XmlTag
 * @author AnhNhan
 *
 */
class XmlTagTest extends AbstractMarkupTest
{
    protected $class = '\\Yamw\\Lib\\Builders\\MarkUp\\XmlTag';

    /**
     * @covers \Yamw\Lib\Builders\MarkUp\XmlTag::getOptions
     * @dataProvider data_options
     */
    public function testGetOptions($attr, $val) {
        $inst = new XmlTag(__CLASS__, __FILE__);
        
        // Verify that the options are empty
        $this->assertCount(0, $inst->getOptions());
        
        $inst->addOption($attr, $val);

        // Verify that the options are filled
        $opt = $inst->getOptions();
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                if (is_numeric($key)) {
                    $this->assertArrayHasKey($value, $opt);
                } else {
                    $this->assertArrayHasKey($key, $opt);
                    $this->assertSame($value, $opt[$key]);
                }
            }
        } else {
            $this->assertSame(array($attr => $val), $opt);
        }
    }
    
    /**
     * @covers \Yamw\Lib\Builders\MarkUp\XmlTag::addOption
     * @dataProvider data_options
     */
    public function testAddOption($attr, $val=null, $count=3)
    {
        $inst = new XmlTag(__CLASS__, __FILE__, array('hey', 'hallo' => 'gsh'));
        $inst->addOption($attr, $val);
        
        $r = $inst->getOptions();
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                if (is_numeric($key)) {
                    $this->assertArrayHasKey($value, $r);
                } else {
                    $this->assertArrayHasKey($key, $r);
                    $this->assertSame($value, $r[$key]);
                }
            }
        } else {
            $this->assertArrayHasKey($attr, $r);
            $this->assertSame($val, $r[$attr]);
        }
        
        $this->assertCount($count, $r);
    }
    
    public function data_options()
    {
        return array(
            array(
                'hey', null, 2
            ),
            array(
                'blam', 'dude'
            ),
            array(
                array('hi'), null
            ),
            array(
                array('mudg', 'sefh'), null, 4
            ),
            array(
                array('sg' => 'sedrvfh'), null, 3
            ),
            array(
                array('zd' => 'es', 'sefh', 'grs'), null, 5
            ),
        );
    }
    
    /**
     * @covers \Yamw\Lib\Builders\MarkUp\XmlTag::addOption
     * @dataProvider data_options_invalid
     * @expectedException InvalidArgumentException
     */
    public function testInvalidaddOption($attr, $val)
    {
        $this->testaddOption($attr, $val);
    }
    
    public function data_options_invalid()
    {
        return array(
            array(
                array(), 'hi'
            ),
            array(
                array(), new XmlTagTest()
            ),
            array(
                'hey', array()
            )
        );
    }
    
    /**
     * @covers \Yamw\Lib\Builders\MarkUp\XmlTag::isSelfClosing
     */
    public function testIsSelfClosing()
    {
        $class = $this->class;
        $inst = new $class('somename');
        $this->assertTrue($inst->isSelfClosing());
        
        $inst->setContent('');
        $this->assertFalse($inst->isSelfClosing());
        
        // Now the other way around
        $inst = new $class('somename', 'some content');
        $this->assertFalse($inst->isSelfClosing());
        
        $inst->setContent(null);
        $this->assertTrue($inst->isSelfClosing());
    }

    /**
     * @covers \Yamw\Lib\Builders\MarkUp\XmlTag::__toString
     * @covers \Yamw\Lib\Builders\MarkUp\XmlTag::addOption
     * @dataProvider data_xml_test
     */
    public function testToString($output, $name, $content, $options)
    {
        $class = $this->class;
        $inst = new $class($name, $content, $options);

        // String concatenation, since this should trigger the __toString method
        $this->assertSame($output, ''.$inst);
    }
    
    public function data_xml_test()
    {
        return array(
            array(
                "    <a href=\"hallo\"></a>\n",
                'a', '',
                array('href' => 'hallo')
            ),
            array(
                "    <a href=\"hallo\">linktext</a>\n",
                'a', 'linktext',
                array('href' => 'hallo')
            ),
            array(
                "    <a href link tag></a>\n",
                'a', '',
                array('href', 'link', 'tag')
            ),
            array(
                "    <a>    <span>Hallo</span>\n</a>\n",
                'a',
                new XmlTag('span', 'Hallo'),
                array()
            ),
            array(
                "    <img src=\"pr0n\" />\n",
                'img', null,
                array('src' => 'pr0n')
            )
        );
    }
    
    public function data_method_chain()
    {
        return array(
            array('setContent', 'somestring'),
            array('appendContent', 'somestring'),
            array('removeContent', null),
            array('addOption', 'hey', 'yeah?')
        );
    }
}
