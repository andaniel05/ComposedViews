<?php

namespace PlatformPHP\ComposedViews\Tests\Asset;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\{AssetsTrait, AbstractAsset};

class AssetsTraitTest extends TestCase
{
    public function testGetAssetsReturnAnEmptyArrayByDefault()
    {
        $trait = $this->getMockForTrait(AssetsTrait::class);

        $this->assertEquals([], $trait->getAssets());
    }

    public function getTrait(array $assets = [])
    {
        $trait = $this->getMockBuilder(AssetsTrait::class)
            ->disableOriginalConstructor()
            ->setMethods(['assets'])
            ->getMockForTrait();
        $trait->expects($this->once())
            ->method('assets')->willReturn($assets);

        $initializeAssetsMethod = new \ReflectionMethod(
            get_class($trait), 'initializeAssets'
        );
        $initializeAssetsMethod->setAccessible(true);
        $initializeAssetsMethod->invoke($trait);

        return $trait;
    }

    public function testGetAssetsReturnAnEmptyArrayWhenAssetsReturnAnEmptyArrayToo()
    {
        $trait = $this->getTrait();

        $this->assertEquals([], $trait->getAssets());
    }

    public function testInitializeAssets_1()
    {
        $asset1Id = uniqid();
        $asset1 = $this->getMockForAbstractClass(AbstractAsset::class, [$asset1Id]);

        $def = [$asset1];
        $trait = $this->getTrait($def);

        $assets = $trait->getAssets();
        $this->assertEquals($asset1, $assets[$asset1Id]);
    }

    public function testInitializeAssets_2()
    {
        $asset1Id = uniqid();
        $asset1 = $this->getMockForAbstractClass(AbstractAsset::class, [$asset1Id]);
        $group1 = uniqid();

        $def = [$group1 => [$asset1]];
        $trait = $this->getTrait($def);

        $assets = $trait->getAssets();
        $this->assertEquals($asset1, $assets[$asset1Id]);
        $this->assertTrue($asset1->inGroup($group1));
    }

    public function testInitializeAssets_3()
    {
        $asset1Id = uniqid();
        $asset1 = $this->getMockForAbstractClass(AbstractAsset::class, [$asset1Id]);
        $group1 = uniqid();
        $group2 = uniqid();

        $def = [
            $group1 => [
                $group2 => [$asset1]
            ]
        ];
        $trait = $this->getTrait($def);

        $assets = $trait->getAssets();
        $this->assertEquals($asset1, $assets[$asset1Id]);
        $this->assertTrue($asset1->inGroup($group1));
        $this->assertTrue($asset1->inGroup($group2));
    }
}