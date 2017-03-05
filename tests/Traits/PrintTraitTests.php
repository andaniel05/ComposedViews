<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PlatformPHP\ComposedViews\Traits\PrintTrait;
use PlatformPHP\ComposedViews\RenderInterface;

trait PrintTraitTests
{
    public function printTraitTestsProvider1()
    {
        return [
            ['result1'], ['result2'],
        ];
    }

    /**
     * @dataProvider printTraitTestsProvider1
     */
    public function testPrintPrintResultOfRenderMethod($renderResult)
    {
        $trait = $this->getMockBuilder($this->getTestClass())
            ->disableOriginalConstructor()
            ->setMethods(['render']);
        $trait = $this->assumeMock($this->getTestClass(), $trait);
        $trait->expects($this->once())
            ->method('render')
            ->willReturn($renderResult);

        $trait->print();

        $this->expectOutputString($renderResult);
    }

    public function testIsPrintedReturnFalseByDefault()
    {
        $trait = $this->getMockBuilder($this->getTestClass())
            ->disableOriginalConstructor();
        $trait = $this->assumeMock($this->getTestClass(), $trait);

        $this->assertFalse($trait->isPrinted());
    }

    public function testIsPrintedReturnTrueAfterPrintInvokation()
    {
        $trait = $this->getMockBuilder($this->getTestClass())
            ->disableOriginalConstructor()
            ->setMethods(['render']);
        $trait = $this->assumeMock($this->getTestClass(), $trait);
        $trait->expects($this->once())
            ->method('render')->willReturn('');

        $trait->print();

        $this->assertTrue($trait->isPrinted());
    }
}