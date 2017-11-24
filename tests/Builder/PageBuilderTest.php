<?php

namespace Andaniel05\ComposedViews\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Builder\PageBuilder;
use Andaniel05\ComposedViews\AbstractPage;

class PageBuilderTest extends TestCase
{
    public function testCreateAnPageInstanceOfClassAttribute()
    {
        $xml = '<page class="Andaniel05\ComposedViews\Tests\Builder\Page"></page>';
        $builder = new PageBuilder;

        $page = $builder->build($xml);

        $this->assertInstanceOf(Page::class, $page);
    }
}
