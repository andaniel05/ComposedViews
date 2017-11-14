<?php

namespace Andaniel05\ComposedViews\Tests\Asset2;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset2\{AssetsTrait, AbstractAsset};

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
        $asset1 = $this->getMockForAbstractClass(AbstractAsset::class);
        setAttr($asset1Id, 'id', $asset1);

        $def = [$asset1];
        $trait = $this->getTrait($def);

        $assets = $trait->getAssets();
        $this->assertEquals($asset1, $assets[$asset1Id]);
    }

    public function testInitializeAssets_2()
    {
        $asset1Id = uniqid();
        $asset1 = $this->getMockForAbstractClass(AbstractAsset::class);
        setAttr($asset1Id, 'id', $asset1);
        $group1 = uniqid();

        $def = [$group1 => [$asset1]];
        $trait = $this->getTrait($def);

        $assets = $trait->getAssets();
        $this->assertEquals($asset1, $assets[$asset1Id]);
        $this->assertTrue($asset1->hasGroup($group1));
    }

    public function testInitializeAssets_3()
    {
        $asset1Id = uniqid();
        $asset1 = $this->getMockForAbstractClass(AbstractAsset::class, [$asset1Id]);
        setAttr($asset1Id, 'id', $asset1);
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
        $this->assertTrue($asset1->hasGroup($group1));
        $this->assertTrue($asset1->hasGroup($group2));
    }
}
