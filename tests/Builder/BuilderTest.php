<?php

namespace Andaniel05\ComposedViews\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Builder\{BuilderInterface, Builder};
use Symfony\Component\EventDispatcher\{EventDispatcherInterface, EventDispatcher};

class BuilderTest extends TestCase
{
    public function setUp()
    {
        $this->builder = new Builder;
    }

    public function testIsInstanceOfBuilderInterface()
    {
        $this->assertInstanceOf(BuilderInterface::class, $this->builder);
    }

    public function testHasAnDispatcherByDefault()
    {
        $this->assertInstanceOf(
            EventDispatcherInterface::class, $this->builder->getDispatcher()
        );
    }
}
