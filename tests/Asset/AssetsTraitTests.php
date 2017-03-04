<?php

namespace PlatformPHP\ComposedViews\Tests\Asset;

trait AssetsTraitTests
{
    public function assetsTraitTestsSetUp()
    {
        $builder = $this->getMockBuilder($this->getTestClass());

        $this->trait = $this->assumeMock($this->getTestClass(), $builder);
    }

    public function testGetAssetsReturnAnEmptyArrayByDefault()
    {
        $this->assetsTraitTestsSetUp();

        $this->assertEquals([], $this->trait->getAssets());
    }

    public function assetsTraitTestsGetMock(array $assets = [])
    {
        $trait = $this->getMockBuilder($this->getTestClass())
            ->setMethods(['assets']);
        $trait = $this->assumeMock($this->getTestClass(), $trait);
        $trait->expects($this->once())
            ->method('assets')->willReturn($assets);

        $initializeAssetsMethod = new \ReflectionMethod(
            get_class($trait), 'initializeAssets'
        );
        $initializeAssetsMethod->setAccessible(TRUE);
        $initializeAssetsMethod->invoke($trait);

        return $trait;
    }

    public function testGetAssetsReturnAnEmptyArrayWhenAssetsReturnAnEmptyArrayToo()
    {
        $trait = $this->assetsTraitTestsGetMock();

        $this->assertEquals([], $trait->getAssets());
    }

    public function testInitializeAssets()
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

        $trait = $this->assetsTraitTestsGetMock($def);

        $assets = $trait->getAssets();
        $bootstrap = $assets['bootstrap'];
        $custom = $assets['custom'];
        $jquery = $assets['jquery'];

        $this->assertCount(3, $assets);

        $this->assertEquals('bootstrap', $bootstrap->getId());
        $this->assertEquals('styles', $bootstrap->getGroup());
        $this->assertEquals('/css/bootstrap.css', $bootstrap->getUrl());
        $this->assertEquals([], $bootstrap->getDependencies());
        $this->assertEquals(null, $bootstrap->getContent());

        $this->assertEquals('custom', $custom->getId());
        $this->assertEquals('styles', $custom->getGroup());
        $this->assertEquals('/css/custom.css', $custom->getUrl());
        $this->assertEquals(['bootstrap'], $custom->getDependencies());
        $this->assertEquals('* {color: black}', $custom->getContent());

        $this->assertEquals('jquery', $jquery->getId());
        $this->assertEquals('scripts', $jquery->getGroup());
        $this->assertEquals('/js/jquery.js', $jquery->getUrl());
        $this->assertEquals([], $jquery->getDependencies());
        $this->assertEquals(null, $jquery->getContent());
    }
}