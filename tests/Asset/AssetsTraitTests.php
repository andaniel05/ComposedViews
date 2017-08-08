<?php

namespace PlatformPHP\ComposedViews\Tests\Asset;

use PlatformPHP\ComposedViews\AbstractPage;
use PlatformPHP\ComposedViews\Asset\Asset;

trait AssetsTraitTests
{
    public function assetsTraitTestsSetUp()
    {
        $builder = $this->getMockBuilder($this->getTestClass())
            ->disableOriginalConstructor();

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
            ->disableOriginalConstructor()
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
        if (AbstractPage::class == $this->getTestClass()) {
            $this->markTestSkipped();
        }

        $script2 = new Asset('script2', 'scripts');

        $def = [
            'styles' => [
                ['bootstrap', '/css/bootstrap.css', [], ],
                ['custom', '/css/custom.css', ['bootstrap'], '* {color: black}'],
            ],
            'scripts' => [
                ['jquery', '/js/jquery.js'],
                $script2,
            ],
            new Asset('script1', 'scripts', '/js/script1.js', ['jquery']),
        ];

        $trait = $this->assetsTraitTestsGetMock($def);

        $assets = $trait->getAssets();
        $bootstrap = $assets['bootstrap'];
        $custom = $assets['custom'];
        $jquery = $assets['jquery'];
        $script1 = $assets['script1'];
        $script2 = $assets['script2'];

        $this->assertCount(5, $assets);

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

        $this->assertEquals('script1', $script1->getId());
        $this->assertEquals('scripts', $script1->getGroup());
        $this->assertEquals('/js/script1.js', $script1->getUrl());
        $this->assertEquals(['jquery'], $script1->getDependencies());
        $this->assertEquals(null, $script1->getContent());

        $this->assertEquals('script2', $script2->getId());
        $this->assertEquals('scripts', $script2->getGroup());
    }
}