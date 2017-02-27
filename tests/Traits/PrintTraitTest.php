<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PlatformPHP\ComposedViews\Traits\PrintTrait;

class PrintTraitTest extends \PHPUnit_Framework_TestCase
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

        ob_start();
        $trait->print();
        $result = ob_get_clean();

        $this->assertEquals($renderResult, $result);
    }
}