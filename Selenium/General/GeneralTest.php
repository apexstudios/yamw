<?php
namespace Selenium\General;

class GeneralTest extends \Tests_Selenium2TestCase_BaseTestCase
{

    public function testStartPageNotDefaultTitle()
    {
        $this->url('/');

        // Just making sure that we are on the right website
        self::assertStringEndsWith('/', $this->url());
        $link = $this->byCssSelector('h1');
        self::assertEquals('This is the HMH Home (Index action)', $link->text());

        self::assertNotContains('YAMW Systems Website', $this->title());
    }

    public function testHasInformationOnStartPage()
    {
        $this->url('/');

        // Just making sure that we are on the right website
        self::assertStringEndsWith('/', $this->url());

        $footer = $this->byId('footer');

        // Version number
        self::assertContains(VERSION, $footer->text());
        // Pagetime
        self::assertRegExp('/[0-9]\.[0-9]+ seconds/', $footer->text());
        // Memory usage
        self::assertRegExp('/[0-9]\.[0-9]+MB/', $footer->text());
    }

    public function testContainerIsProperlyLinedUp()
    {
        $this->url('/');

        // Just making sure that we are on the right website
        self::assertStringEndsWith('/', $this->url());

        $body = $this->byCssSelector('body');
        $body_width = $body->size();
        $body_width = $body_width['width'];

        if ($body_width < 900) {
            $this->markTestSkipped('Window is smaller than 900px, all layout tests are futile.');
        }

        $container = $this->byId('container');
        $container_x = $container->location();
        $container_x = $container_x['x'];

        $container_distance = ($body_width - 900) / 2;

        self::assertEquals($container_distance, $container_x, 'Container not properly aligned', 15);
    }
}
