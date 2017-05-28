<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PlatformPHP\ComposedViews\Traits\PrintTrait;
use PlatformPHP\ComposedViews\HtmlInterface;

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
    public function testPrintPrintResultOfHtmlMethod($htmlResult)
    {
        $trait = $this->getMockBuilder($this->getTestClass())
            ->disableOriginalConstructor()
            ->setMethods(['html']);
        $trait = $this->assumeMock($this->getTestClass(), $trait);
        $trait->expects($this->once())
            ->method('html')
            ->willReturn($htmlResult);

        $trait->print();

        $this->expectOutputString($htmlResult);
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
            ->setMethods(['html']);
        $trait = $this->assumeMock($this->getTestClass(), $trait);
        $trait->expects($this->once())
            ->method('html')->willReturn('');

        $trait->print();

        $this->assertTrue($trait->isPrinted());
    }
}