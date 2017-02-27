<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Traits\PrintTrait;

class PrintTraitTest extends TestCase
{
    public function provider1()
    {
        return [
            ['result1'], ['result2'],
        ];
    }

    /**
     * @dataProvider provider1
     */
    public function testPrint_PrintResultOfRenderMethod($renderResult)
    {
        $trait = $this->getMockBuilder(PrintTrait::class)
            ->setMethods(['render'])
            ->getMockForTrait();
        $trait->expects($this->once())
            ->method('render')
            ->willReturn($renderResult);

        $trait->print();

        $this->expectOutputString($renderResult);
    }
}