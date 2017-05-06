<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PlatformPHP\ComposedViews\AbstractPage;
use PlatformPHP\ComposedViews\Component\AbstractComponent;

trait PageTraitTests
{
    public function testGetPage_ReturnNullByDefault()
    {
        $component1 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component1'])
            ->getMockForAbstractClass();

        $this->assertNull($component1->getPage());
    }

    public function testGetPage_ReturnTheInsertedPage()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);
        $component1 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component1'])
            ->getMockForAbstractClass();

        $component1->setPage($page);

        $this->assertSame($page, $component1->getPage());
    }
}