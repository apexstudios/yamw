<?php
use Yamw\Lib\Builders\Markup\XmlTag;

/**
 * @author AnhNhan
 */
class XmlTagTest extends AbstractMarkupTest
{
    protected $class = '\\Yamw\\Lib\\Builders\\Markup\\XmlTag';

    /**
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
     * @dataProvider data_xml_test
     */
    public function testToString($output, $name, $content, $options)
    {
        $class = $this->class;
        $inst = new $class($name, $content, $options);

        // String concatenation, since this should trigger the __toString method
        $this->assertXmlStringEqualsXmlString($output, ''.$inst);
    }

    public function data_xml_test()
    {
        return array(
            array(
                "<a href=\"hallo\"></a>",
                'a', '',
                array('href' => 'hallo')
            ),
            array(
                "<a href=\"hallo\">linktext</a>",
                'a', 'linktext',
                array('href' => 'hallo')
            ),
            array(
                "<a href=\"I'm cool\" link=\"I'm link\" tag=\"Something else\"></a>",
                'a', '',
                array('href' => 'I\'m cool', 'link' => 'I\'m link', 'tag' => 'Something else')
            ),
            array(
                "<a><span>Hallo</span>\n</a>",
                'a',
                new XmlTag('span', 'Hallo'),
                array()
            ),
            array(
                "<img src=\"pr0n\" />",
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
