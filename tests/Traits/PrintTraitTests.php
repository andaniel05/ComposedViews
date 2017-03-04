<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PlatformPHP\ComposedViews\Traits\PrintTrait;

trait PrintTraitTests
{
    public function printTraitTests_Provider1()
    {
        return [
            ['result1'], ['result2'],
        ];
    }

    /**
     * @dataProvider printTraitTests_Provider1
     */
    public function testPrint_PrintResultOfRenderMethod($renderResult)
    {
        $trait = $this->getMockBuilder($this->getTestClass())
            ->setMethods(['render']);
        $trait = $this->assumeMock($this->getTestClass(), $trait);
        $trait->expects($this->once())
            ->method('render')
            ->willReturn($renderResult);

        $trait->print();

        $this->expectOutputString($renderResult);
    }

    // public function testIsPrinted_ReturnFalseByDefault()
    // {
    //     $trait = $this->getMockForTrait($this->getTestClass());

    //     $this->assertFalse($trait->isPrinted());
    // }

    // public function testIsPrinted_ReturnTrueAfterPrintInvokation()
    // {
    //     $trait = $this->getMockBuilder($this->getTestClass())
    //         ->setMethods(['render'])
    //         ->getMockForTrait();
    //     $trait->expects($this->once())
    //         ->method('render')->willReturn('');

    //     $trait->print();

    //     $this->assertTrue($trait->isPrinted());
    // }
}