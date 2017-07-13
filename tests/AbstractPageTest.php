<?php

namespace PlatformPHP\ComposedViews\Tests;

use PlatformPHP\ComposedViews\{AbstractPage, PageEvents};
use PlatformPHP\ComposedViews\Event\FilterAssetsEvent;
use PlatformPHP\ComposedViews\Component\AbstractComponent;
use PlatformPHP\ComposedViews\Sidebar\Sidebar;
use PlatformPHP\ComposedViews\Asset\Asset;
use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Tests\Traits\{PrintTraitTests, CloningTraitTests};
use PlatformPHP\ComposedViews\Tests\Asset\AssetsTraitTests;
use Symfony\Component\EventDispatcher\{EventDispatcherInterface,
    EventDispatcher};

class AbstractPageTest extends TestCase
{
    use PrintTraitTests, AssetsTraitTests, CloningTraitTests;

    public function setUp()
    {
        $this->page = $this->getMockForAbstractClass(AbstractPage::class);
    }

    public function getTestClass()
    {
        return AbstractPage::class;
    }

    public function testAssetsInitializationOnConstructor()
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['initializeAssets'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('initializeAssets');

        $page->__construct();
    }

    public function testVarsInitializationOnConstructor()
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['initializeVars'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('initializeVars');

        $page->__construct();
    }

    public function testGetVarsReturnAnEmptyArrayByDefault()
    {
        $this->assertEquals([], $this->page->getAllVars());
    }

    public function provider1()
    {
        return [
            [ [] ],
            [ ['var1' => 'value1'] ],
        ];
    }

    /**
     * @dataProvider provider1
     */
    public function testGetVarsReturnSameResultThatVarsAfterInitialization($vars)
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['vars'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('vars')->willReturn($vars);

        $page->__construct();

        $this->assertEquals($vars, $page->getAllVars());
    }

    public function getMock1()
    {
        $vars = [
            'var1' => 'value1',
            'value2',
            'var3' => 'value3',
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

    public function testGetVarReturnTheValueOfTheVar()
    {
        $page = $this->getMock1();

        $this->assertEquals('value1', $page->getVar('var1'));
        $this->assertEquals('value2', $page->getVar(0));
        $this->assertEquals('value3', $page->getVar('var3'));
    }

    public function testGetVarReturnNullIfVarNotExists()
    {
        $page = $this->getMock1();

        $this->assertNull($page->getVar('var100'));
    }

    public function testSetVarChangeTheValueOfTheVar()
    {
        $page = $this->getMock1();

        $page->setVar('var1', 'new value1');
        $page->setVar(0, 'new value2');

        $this->assertEquals('new value1', $page->getVar('var1'));
        $this->assertEquals('new value2', $page->getVar(0));
    }

    public function testSetVarDoNotInsertNewVars()
    {
        $page = $this->getMock1();

        $page->setVar('var100', 'value100');

        $expectedVars = [
            'var1' => 'value1',
            'value2',
            'var3' => 'value3',
        ];

        $this->assertEquals($expectedVars, $page->getAllVars());
    }

    public function testGetVarsReturnAnArrayWithAllChangedVars()
    {
        $page = $this->getMock1();

        $page->setVar('var1', 'new value1');
        $page->setVar(0, 'new value2');
        $page->setVar('var3', 'new value3');

        $expectedVars = [
            'var1' => 'new value1',
            'new value2',
            'var3' => 'new value3',
        ];

        $this->assertEquals($expectedVars, $page->getAllVars());
    }

    public function testPrintVarDoNotPrintNothingIfVarNotExists()
    {
        $page = $this->getMock1();

        $page->printVar('var100');

        $this->expectOutputString(null);
    }

    public function testPrintVarPrintInTheOutputTheValueOfVar()
    {
        $page = $this->getMock1();

        $page->printVar('var1');

        $this->expectOutputString('value1');
    }

    public function testSidebarInitializationOnConstructor()
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['initializeSidebars'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('initializeSidebars');

        $page->__construct();
    }

    public function testSidebarsReturnAnEmptyArrayByDefault()
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->assertEquals([], $page->getAllSidebars());
    }

    public function getMock2(array $sidebars)
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['sidebars'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('sidebars')->willReturn($sidebars);
        $page->__construct();

        return $page;
    }

    public function testGetSidebarsReturnAnEmptyArrayWhenSidebarsReturnAnEmptyArrayToo()
    {
        $page = $this->getMock2([]);

        $this->assertEquals([], $page->getAllSidebars());
    }

    public function provider2()
    {
        return [
            [ [], 0 ],
            [ ['sidebar1'], 1 ],
            [ ['sidebar1', 'sidebar2'], 2 ],
            [ ['sidebar1', 1, 123.45, true, null, 'sidebar2'], 2 ],
        ];
    }

    /**
     * @dataProvider provider2
     */
    public function testNumberOfSidebarsPerResultsOfSidebars($def, $total)
    {
        $page = $this->getMock2($def);

        $sidebars = $page->getAllSidebars();

        $this->assertCount($total, $sidebars);
        $this->assertContainsOnlyInstancesOf(Sidebar::class, $sidebars);
    }

    public function testGetSidebarReturnNullIfSidebarNotExists()
    {
        $page = $this->getMock2(['sidebar1', 'sidebar2']);

        $this->assertNull($page->getSidebar('sidebar3'));
    }

    public function testGetSidebarReturnTheSidebarIfExists()
    {
        $page = $this->getMock2(['sidebar1', 'sidebar2']);

        $sidebar1 = $page->getSidebar('sidebar1');

        $this->assertInstanceOf(Sidebar::class, $sidebar1);
        $this->assertEquals('sidebar1', $sidebar1->getId());
    }

    public function testInsertionOfComponentsInSidebarOnDefinition()
    {
        $component1 = $this->createMock(AbstractComponent::class);
        $component1->method('getId')->willReturn('component1');

        $component2 = $this->createMock(AbstractComponent::class);
        $component2->method('getId')->willReturn('component2');

        $page = $this->getMock2([
            'sidebar1' => [
                $component1, 'string', true, null, 123,
                123.45, $component2,
            ],
        ]);

        $sidebar1 = $page->getSidebar('sidebar1');

        $expected = [
            'component1' => $component1,
            'component2' => $component2,
        ];

        $this->assertEquals($expected, $sidebar1->getAllComponents());
    }

    public function testPrintSidebarInvokePrintMethodInTheSidebar()
    {
        $sidebar1 = $this->getMockBuilder(Sidebar::class)
            ->disableOriginalConstructor()
            ->setMethods(['print'])
            ->getMock();
        $sidebar1->expects($this->once())
            ->method('print');

        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSidebar'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('getSidebar')
            ->with($this->equalTo('sidebar1'))
            ->willReturn($sidebar1);

        $page->printSidebar('sidebar1');
    }

    public function initializeSidebarsWithEqualsComponents()
    {
        $this->component1 = $this->createMock(AbstractComponent::class);
        $this->component1->method('getId')->willReturn('component1');

        $this->component2 = $this->createMock(AbstractComponent::class);
        $this->component2->method('getId')->willReturn('component1');

        $this->page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['sidebars'])
            ->getMockForAbstractClass();
        $this->page->method('sidebars')
            ->willReturn([
                'sidebar1' => [$this->component1],
                'sidebar2' => [$this->component2],
            ]);

        $this->page->__construct();
    }

    public function testGetComponentReturnTheFirstComponentWhereIdMatch()
    {
        $this->initializeSidebarsWithEqualsComponents();

        $this->assertSame(
            $this->component1,
            $this->page->getComponent('component1')
        );
    }

    public function testGetComponentSearchTheComponentInsideTheSidebar()
    {
        $this->initializeSidebarsWithEqualsComponents();

        $this->assertSame(
            $this->component2,
            $this->page->getComponent('sidebar2 component1')
        );
    }

    public function provider3()
    {
        return [
            ['component1 component2'],
            ['component3 component4'],
        ];
    }

    /**
     * @dataProvider provider3
     */
    public function testGetComponentDelegateIngetComponentFromAllSidebarsWhenPatternNotStartBySidebarId($componentId)
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['getComponentFromAllSidebars'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('getComponentFromAllSidebars')
            ->with($this->equalTo($componentId));
        $page->__construct();

        $page->getComponent($componentId);
    }

    public function testGetPageAssetsReturnAnEmptyArrayByDefault()
    {
        $this->assertEquals([], $this->page->getPageAssets());
    }

    public function testGetPageAssetsIsAliasOfAssetsTraitGetAssets()
    {
        $def = [
            'styles' => [
                ['bootstrap', '/css/bootstrap.css', [], ],
                ['custom', '/css/custom.css', ['bootstrap'], '* {color: black}'],
            ],
            'scripts' => [
                ['jquery', '/js/jquery.js'],
            ],
        ];

        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['assets'])
            ->getMockForAbstractClass();
        $page->method('assets')->willReturn($def);
        $page->__construct();

        $this->assertCount(3, $page->getPageAssets());
    }

    public function initializeAssetDummies()
    {
        $this->bootstrapCss = new Asset('bootstrap-css', 'styles', 'http://localhost/css/bootstrap.css');
        $this->styles       = new Asset('styles', 'styles', 'http://localhost/css/styles.css');
        $this->jquery       = new Asset('jquery', 'scripts', 'http://localhost/js/jquery.js');
        $this->bootstrapJs  = new Asset('bootstrap-js', 'scripts', 'http://localhost/js/bootstrap.js');
        $this->scripts      = new Asset('scripts', 'scripts', 'http://localhost/js/scripts.js');
        $this->customJs      = new Asset('custom-js', 'scripts', 'http://localhost/js/custom-js.js');

        $this->assets = [
            'bootstrap-css' => $this->bootstrapCss,
            'styles'        => $this->styles,
            'jquery'        => $this->jquery,
            'bootstrap-js'  => $this->bootstrapJs,
            'scripts'       => $this->scripts,
            'custom-js'     => $this->customJs,
        ];
    }

    public function initialization1()
    {
        $this->initializeAssetDummies();

        $this->component1 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component1'])
            ->setMethods(['getAssets'])
            ->getMockForAbstractClass();
        $this->component1->method('getAssets')
            ->willReturn([
                $this->bootstrapCss->getId() => $this->bootstrapCss,
            ]);

        $this->component2 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component2'])
            ->setMethods(['getAssets'])
            ->getMockForAbstractClass();
        $this->component2->method('getAssets')
            ->willReturn([
                $this->styles->getId() => $this->styles,
            ]);

        $this->component3 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component3'])
            ->setMethods(['getAssets'])
            ->getMockForAbstractClass();
        $this->component3->method('getAssets')
            ->willReturn([
                $this->jquery->getId() => $this->jquery,
            ]);

        $this->component4 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component4'])
            ->setMethods(['getAssets'])
            ->getMockForAbstractClass();
        $this->component4->method('getAssets')
            ->willReturn([
                $this->bootstrapJs->getId() => $this->bootstrapJs,
            ]);

        $this->component5 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component5'])
            ->setMethods(['getAssets'])
            ->getMockForAbstractClass();
        $this->component5->method('getAssets')
            ->willReturn([
                $this->scripts->getId() => $this->scripts,
            ]);

        $this->page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['sidebars', 'assets'])
            ->getMockForAbstractClass();
        $this->page->method('sidebars')->willReturn(['sidebar1', 'sidebar2']);
        $this->page->method('assets')->willReturn([$this->customJs]);
        $this->page->__construct();

        $this->sidebar1 = $this->page->getSidebar('sidebar1');
        $this->sidebar2 = $this->page->getSidebar('sidebar2');
    }

    public function assertsForAssetsTest()
    {
        $assets = $this->page->getAllAssets();

        $this->assertCount(6, $assets);
        $this->assertSame($this->bootstrapCss, $assets['bootstrap-css']);
        $this->assertSame($this->styles, $assets['styles']);
        $this->assertSame($this->jquery, $assets['jquery']);
        $this->assertSame($this->bootstrapJs, $assets['bootstrap-js']);
        $this->assertSame($this->scripts, $assets['scripts']);
        $this->assertSame($this->customJs, $assets['custom-js']);
    }

    public function testGetAllAssetsCase1()
    {
        $this->initialization1();

        $this->component1->addComponent($this->component2);
        $this->component2->addComponent($this->component3);
        $this->component3->addComponent($this->component4);
        $this->component4->addComponent($this->component5);

        $this->sidebar1->addComponent($this->component1);

        $this->assertsForAssetsTest();
    }

    public function testGetAllAssetsCase2()
    {
        $this->initialization1();

        $this->component1->addComponent($this->component2);
        $this->component2->addComponent($this->component3);

        $this->component4->addComponent($this->component5);

        $this->sidebar1->addComponent($this->component1);
        $this->sidebar2->addComponent($this->component4);

        $this->assertsForAssetsTest();
    }

    public function testGetAssetReturnNullWhenAssetNotExists()
    {
        $this->initialization1();

        $this->assertNull($this->page->getAsset('custom-css'));
    }

    public function testGetAssetReturnTheAssetWhenExists()
    {
        $this->initialization1();

        $this->component1->addComponent($this->component2);
        $this->component2->addComponent($this->component3);
        $this->component3->addComponent($this->component4);
        $this->component4->addComponent($this->component5);

        $this->sidebar1->addComponent($this->component1);

        $this->assertSame($this->jquery, $this->page->getAsset('jquery'));
        $this->assertSame($this->customJs, $this->page->getAsset('custom-js'));
    }

    public function provider4()
    {
        return [
            [ ['asset1' => new Asset('asset1', 'group1', 'url1')] ],
            [ ['asset2' => new Asset('asset2', 'group2', 'url2')] ],
        ];
    }

    /**
     * @dataProvider provider4
     */
    public function testGetAssetsReturnResultOfGetAllAssetsWhenGroupIsNull($assets)
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->setMethods(['getAllAssets'])
            ->getMockForAbstractClass();
        $page->expects($this->once())
            ->method('getAllAssets')
            ->willReturn($assets);

        $this->assertEquals($assets, $page->getAssets());
    }

    public function initialization2()
    {
        $this->initializeAssetDummies();

        $this->page = $this->getMockBuilder(AbstractPage::class)
            ->setMethods(['getAllAssets'])
            ->getMockForAbstractClass();
        $this->page->expects($this->once())
            ->method('getAllAssets')
            ->willReturn($this->assets);
    }

    public function testGetAssetsFilterAssetsByGroup1()
    {
        $this->initialization2();

        $expected = [
            'bootstrap-css' => $this->bootstrapCss,
            'styles' => $this->styles,
        ];

        $this->assertEquals($expected, $this->page->getAssets('styles'));
    }

    public function testGetAssetsFilterAssetsByGroup2()
    {
        $this->initialization2();

        $expected = [
            'jquery'       => $this->jquery,
            'bootstrap-js' => $this->bootstrapJs,
            'scripts'      => $this->scripts,
            'custom-js'    => $this->customJs,
        ];

        $this->assertEquals($expected, $this->page->getAssets('scripts'));
    }

    public function initializeAssetsForOrderingTest()
    {
        $this->asset1 = new Asset('asset1', 'group', 'url');
        $this->asset2 = new Asset('asset2', 'group', 'url', ['asset1']);
        $this->asset3 = new Asset('asset3', 'group', 'url', ['asset2']);
        $this->asset4 = new Asset('asset4', 'group', 'url', ['asset3']);
        $this->asset5 = new Asset('asset5', 'group', 'url', ['asset4']);

        $this->assets = [
            'asset1' => $this->asset1,
            'asset2' => $this->asset2,
            'asset3' => $this->asset3,
            'asset4' => $this->asset4,
            'asset5' => $this->asset5,
        ];
    }

    public function provider5()
    {
        $this->initializeAssetsForOrderingTest();

        return [
            [[
                'asset1' => $this->asset1,
                'asset2' => $this->asset2,
                'asset3' => $this->asset3,
                'asset4' => $this->asset4,
                'asset5' => $this->asset5,
            ]],
            [[
                'asset3' => $this->asset3,
                'asset1' => $this->asset1,
                'asset5' => $this->asset5,
                'asset4' => $this->asset4,
                'asset2' => $this->asset2,
            ]],
            [[
                'asset5' => $this->asset5,
                'asset4' => $this->asset4,
                'asset3' => $this->asset3,
                'asset2' => $this->asset2,
                'asset1' => $this->asset1,
            ]],
        ];
    }

    /**
     * @dataProvider provider5
     */
    public function testGetOrderedAssetsReturnAnArrayWithAssetsInOrder($assets)
    {
        $this->initializeAssetsForOrderingTest();

        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAllAssets'])
            ->getMockForAbstractClass();
        $page->method('getAllAssets')->willReturn($assets);

        $keys = array_keys($page->getOrderedAssets());

        $this->assertEquals('asset1', $keys[0]);
        $this->assertEquals('asset2', $keys[1]);
        $this->assertEquals('asset3', $keys[2]);
        $this->assertEquals('asset4', $keys[3]);
        $this->assertEquals('asset5', $keys[4]);
    }

    public function initialization3()
    {
        $this->initializeAssetsForOrderingTest();

        $this->asset2->setUsed(true);
        $this->asset3->setUsed(true);

        $this->page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['getOrderedAssets'])
            ->getMockForAbstractClass();
        $this->page->method('getOrderedAssets')->willReturn($this->assets);
    }

    public function testGetAssetsExcludeTheUsed()
    {
        $this->initialization3();

        $expected = [
            'asset1' => $this->asset1,
            'asset4' => $this->asset4,
            'asset5' => $this->asset5,
        ];

        $this->assertEquals($expected, $this->page->getAssets());
    }

    public function testGetAssetsNotExcludeTheUsedIfFilterUnUsedIsFalse()
    {
        $this->initialization3();

        $expected = [
            'asset1' => $this->asset1,
            'asset2' => $this->asset2,
            'asset3' => $this->asset3,
            'asset4' => $this->asset4,
            'asset5' => $this->asset5,
        ];

        $this->assertEquals($expected, $this->page->getAssets(null, false));
    }

    public function testGetAssetsMarkTheAssetsAsUsedByDefault()
    {
        $this->initialization3();

        $this->page->getAssets();

        $this->assertTrue($this->asset1->isUsed());
        $this->assertTrue($this->asset4->isUsed());
        $this->assertTrue($this->asset5->isUsed());
    }

    public function testGetAssetsDoNotMarkTheAssetsAsUsedWhenMarkUsedIsFalse()
    {
        $this->initialization3();

        $this->page->getAssets(null, true, false);

        $this->assertFalse($this->asset1->isUsed());
        $this->assertFalse($this->asset4->isUsed());
        $this->assertFalse($this->asset5->isUsed());
    }

    public function testMagicGetterReturnNullIfComponentIdNotExists()
    {
        $this->assertNull($this->page->component100);
    }

    public function testMagicGetterReturnTheComponentIfExists()
    {
        $this->initialization1();

        $this->sidebar1->addComponent($this->component1);

        $this->assertSame(
            $this->component1,
            $this->page->component1
        );
    }

    public function initializeEntities()
    {
        $this->component1 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component1'])
            ->getMockForAbstractClass();

        $this->component2 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component2'])
            ->getMockForAbstractClass();

        $this->component3 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component3'])
            ->getMockForAbstractClass();

        $this->component4 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component4'])
            ->getMockForAbstractClass();

        $this->component5 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component5'])
            ->getMockForAbstractClass();

        $this->page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['sidebars'])
            ->getMockForAbstractClass();
        $this->page->method('sidebars')->willReturn(['sidebar1', 'sidebar2']);
        $this->page->__construct();

        $this->sidebar1 = $this->page->getSidebar('sidebar1');
        $this->sidebar2 = $this->page->getSidebar('sidebar2');
    }

    public function testComponentsReturnAnGeneratorForTraverseTheComponentTree1()
    {
        // Arrange
        $this->initializeEntities();

        // Act
        //

        $this->sidebar1->addComponent($this->component1);
        $this->component1->addComponent($this->component2);
        $this->component2->addComponent($this->component3);
        $this->component3->addComponent($this->component4);
        $this->component4->addComponent($this->component5);

        $components = $this->page->components();

        // Asserts
        //

        $this->assertSame($this->component1, $components->current());

        $components->next();
        $this->assertSame($this->component2, $components->current());

        $components->next();
        $this->assertSame($this->component3, $components->current());

        $components->next();
        $this->assertSame($this->component4, $components->current());

        $components->next();
        $this->assertSame($this->component5, $components->current());
    }

    public function testComponentsReturnAnGeneratorForTraverseTheComponentTree2()
    {
        // Arrange
        $this->initializeEntities();

        // Act
        //

        $this->sidebar1->addComponent($this->component1);

        $this->component1->addComponent($this->component2);
        $this->component1->addComponent($this->component3);

        $this->component2->addComponent($this->component4);
        $this->component4->addComponent($this->component5);

        $components = $this->page->components();

        // Asserts
        //

        $this->assertSame($this->component1, $components->current());

        $components->next();
        $this->assertSame($this->component2, $components->current());

        $components->next();
        $this->assertSame($this->component4, $components->current());

        $components->next();
        $this->assertSame($this->component5, $components->current());

        $components->next();
        $this->assertSame($this->component3, $components->current());
    }

    public function testBaseUrlReturnAnEmptyStringByDefault()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);

        $this->assertEquals('', $page->baseUrl());
    }

    public function testBaseUrlReturnTheBaseUrlArgument()
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->setConstructorArgs(['http://localhost/'])
            ->getMockForAbstractClass();

        $this->assertEquals('http://localhost/', $page->baseUrl());
    }

    public function provider6()
    {
        return [
            ['', 'http://localhost/jquery.js', 'http://localhost/jquery.js'],
            ['http://localhost/', 'jquery.js', 'http://localhost/jquery.js'],
        ];
    }

    /**
     * @dataProvider provider6
     */
    public function testBaseUrlReturnMixOfBaseUrlAndAssetUrl($baseUrl, $assetUrl, $expected)
    {
        $page = $this->getMockBuilder(AbstractPage::class)
            ->setConstructorArgs([$baseUrl])
            ->getMockForAbstractClass();

        $this->assertEquals($expected, $page->baseUrl($assetUrl));
    }

    public function testGetAllAssetsIncludeInTheResultArrayInsertionsByAddAsset()
    {
        $this->initialization1();
        $this->component1->addComponent($this->component2);
        $this->sidebar1->addComponent($this->component1);

        $newAsset = new Asset('new-asset', '', '');
        $this->page->addAsset($newAsset);

        $assets = $this->page->getAllAssets();
        $this->assertSame($newAsset, $assets['new-asset']);
    }

    /**
     * @expectedException PlatformPHP\ComposedViews\Exception\AssetNotFoundException
     */
    public function testGetAssetsThrowAssetNotFoundExceptionWhenADependencyDoesNotFind()
    {
        $assets = [
            'bootstrap-js' => new Asset('bootstrap-js', 'scripts', 'http://localhost/bootstrap.js', ['jquery'])
        ];

        $page = $this->getMockBuilder(AbstractPage::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAllAssets'])
            ->getMockForAbstractClass();
        $page->method('getAllAssets')
            ->willReturn($assets);

        $page->getAssets();
    }

    public function testGetDispatcherReturnAnInstanceOfSymfonyEventDispatcherClass()
    {
        $page = $this->getMockForAbstractClass(AbstractPage::class);

        $this->assertInstanceOf(
            EventDispatcherInterface::class, $page->getDispatcher()
        );
    }

    public function testGetDispatcherReturnTheDispatcherArgument()
    {
        $dispatcher = new EventDispatcher();

        $page = $this->getMockBuilder(AbstractPage::class)
            ->setConstructorArgs(['http://localhost/', $dispatcher])
            ->getMockForAbstractClass();

        $this->assertSame($dispatcher, $page->getDispatcher());
    }

    public function testPageFilterAssetsEvent()
    {
        $assets = [
            'jquery' => new Asset('jquery', 'script', 'http://localhost/jquery.js'),
        ];

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(PageEvents::FILTER_ASSETS, function (FilterAssetsEvent $event) {
            $assets = $event->getAssets();
            unset($assets['jquery']);
            $event->setAssets($assets);
        });

        $page = $this->getMockBuilder(AbstractPage::class)
            ->setConstructorArgs(['', $dispatcher])
            ->setMethods(['getAllAssets'])
            ->getMockForAbstractClass();
        $page->method('getAllAssets')->willReturn($assets);

        $filteredAssets = $page->getAssets();

        $this->assertEmpty($filteredAssets);
    }
}