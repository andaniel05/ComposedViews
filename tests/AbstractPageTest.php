<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\AbstractPage;

class AbstractPageTest extends TestCase
{
    public function setUp()
    {
        $this->page = $this->getMockForAbstractClass(AbstractPage::class);
    }

    public function testGetVars_ReturnAnEmptyArrayByDefault()
    {
        $vars = $this->page->getVars();

        $this->assertInternalType('array', $vars);
        $this->assertEmpty($vars);
    }

    public function provider1() : array
    {
        return [
            [array('key1' => 'value1', 'key2' => 'value2')],
            [array('key3' => 'value3', 'key4' => 'value4')],
        ];
    }

    /**
     * @dataProvider provider1
     */
    public function testGetVars_ReturnAnArrayWithEqualsKeysToVarsResult(array $vars)
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['vars'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('vars')->willReturn($vars);

        $page->__construct();

        $keys1 = array_keys($vars);
        $keys2 = array_keys($page->getVars());

        $this->assertEquals($keys1, $keys2);
    }

    public function testGetVar_ReturnNullWhenKeyNotExists()
    {
        $this->assertNull($this->page->getVar('var'));
    }

    public function getMock1()
    {
        $vars = [
            'var1' => 'value1',
            'var2' => 'value2',
        ];

        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['vars'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('vars')->willReturn($vars);
        $page->__construct();

        return $page;
    }

    public function testGetVar_ReturnValueOfTheVarIfExists()
    {
        $page = $this->getMock1();

        $this->assertEquals('value1', $page->getVar('var1'));
        $this->assertEquals('value2', $page->getVar('var2'));
    }

    public function provider2()
    {
        return [
            ['var1', 'new value1'],
            ['var2', 'new value2'],
        ];
    }

    /**
     * @dataProvider provider2
     */
    public function testSetVar_ChangeTheValueOfTheVar($var, $value)
    {
        $page = $this->getMock1();

        $page->setVar($var, $value);

        $this->assertEquals($value, $page->getVar($var));
    }

    public function provider3()
    {
        return [
            ['value4', 'value5'],
            ['value6', 'value7'],
        ];
    }

    /**
     * @dataProvider provider3
     */
    public function testGetVars_ReturnArrayWithAllChangesDoingsForSetVar($var1, $var2)
    {
        $page = $this->getMock1();

        $page->setVar('var1', $var1);
        $page->setVar('var2', $var2);

        $this->assertEquals(
            ['var1' => $var1, 'var2' => $var2],
            $page->getVars()
        );
    }

    public function testSetVar_DoNotInsertNewVars()
    {
        $page = $this->getMock1();

        $page->setVar('var100', 'value100');
        $page->setVar('var101', 'value101');

        $this->assertEquals(
            ['var1' => 'value1', 'var2' => 'value2'],
            $page->getVars()
        );
    }

    public function testPrintVar_DoNotPrintNoneIfVarNotExists()
    {
        $page = $this->getMock1();

        $page->printVar('var100');

        $this->expectOutputString('');
    }

    public function provider4()
    {
        return [
            ['var1', 'value1'],
            ['var2', 'value2'],
        ];
    }

    /**
     * @dataProvider provider4
     */
    public function testPrintVar_PrintValueOfVarIfExists($var, $value)
    {
        $page = $this->getMock1();

        $page->printVar($var);

        $this->expectOutputString($value);
    }
}