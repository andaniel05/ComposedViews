<?php

namespace Andaniel05\ComposedViews\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Builder\PageBuilder;
use Andaniel05\ComposedViews\AbstractPage;

class PageBuilderTest extends TestCase
{
    public function setUp()
    {
        $this->builder = new PageBuilder;
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
}
