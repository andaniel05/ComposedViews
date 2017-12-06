<?php

namespace Andaniel05\ComposedViews\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Builder\PageBuilder;
use Andaniel05\ComposedViews\AbstractPage;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class PageBuilderTest extends TestCase
{
    public function setUp()
    {
        $this->builder = new PageBuilder;
        $this->builder->onTag('component', function ($event) {
            $component = new Component(uniqid());
            $event->setEntity($component);
        });
    }

    public function testCreateAnPageInstanceOfClassAttribute()
    {
        $xml = '<page class="Andaniel05\ComposedViews\Tests\Builder\Page"></page>';

        $page = $this->builder->build($xml);

        $this->assertInstanceOf(Page::class, $page);
    }

    public function testBasePathAttribute()
    {
        $basePath = uniqid();
        $xml = <<<XML
<page class="Andaniel05\ComposedViews\Tests\Builder\Page"
      base-path="{$basePath}"></page>
XML;
        $page = $this->builder->build($xml);

        $this->assertEquals($basePath, $page->basePath());
    }

    public function providerInvalidClass()
    {
        return [
            ['<page></page>'],
            ['<page class=""></page>'],
            ['<page class="'.uniqid().'"></page>'],
            ['<page class="'.\stdClass::class.'"></page>'],
        ];
    }

    /**
     * @dataProvider providerInvalidClass
     * @expectedException Andaniel05\ComposedViews\Builder\Exception\InvalidPageClassException
     */
    public function testThrowLostClassAttributeException($xml)
    {
        $this->builder->build($xml);
    }

    public function testPageTagPopulation1()
    {
        $id = uniqid('comp');
        $xml = <<<XML
<page class="Andaniel05\ComposedViews\Tests\Builder\Page">
    <sidebar id="sidebar1">
        <component id="{$id}"></component>
    </sidebar>
</page>
XML;

        $page = $this->builder->build($xml);
        $sidebar1 = $page->getSidebar('sidebar1');
        $sidebar2 = $page->getSidebar('sidebar2');
        $component = $sidebar1->getChild($id);

        $this->assertEquals($sidebar1, $component->getParent());
        $this->assertEmpty($sidebar2->getChildren());
    }

    public function testPageTagPopulation2()
    {
        $xml = <<<XML
<page class="Andaniel05\ComposedViews\Tests\Builder\Page">

    <sidebar id="sidebar1">
        <component id="component1">
            <component id="component2"></component>
            <component id="component3"></component>
        </component>
    </sidebar>

    <sidebar id="sidebar2">
        <component id="component4">
            <component id="component5">
                <component id="component6"></component>
            </component>
        </component>
    </sidebar>

</page>
XML;

        $page = $this->builder->build($xml);

        $this->assertEquals($page->sidebar1, $page->component1->getParent());
        $this->assertEquals($page->component1, $page->component2->getParent());
        $this->assertEquals($page->component1, $page->component3->getParent());

        $this->assertEquals($page->sidebar2, $page->component4->getParent());
        $this->assertEquals($page->component4, $page->component5->getParent());
        $this->assertEquals($page->component5, $page->component6->getParent());
    }
}
