<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PlatformPHP\ComposedViews\Component\AbstractComponent;

class AbstractComponentTest extends \PHPUnit_Framework_TestCase
{
    public function provider1()
    {
        return [
            ['component1'],
            ['component2'],
        ];
    }

    /**
     * @dataProvider provider1
     */
    public function testArgumentGetters($id)
    {
        $component = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs([$id])
            ->getMockForAbstractClass();

        $this->assertEquals($id, $component->getId());
    }
}