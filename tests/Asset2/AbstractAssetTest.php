<?php

namespace Andaniel05\ComposedViews\Tests\Asset2;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset2\{AbstractAsset, AssetInterface};
use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

class AbstractAssetTest extends TestCase
{
    public function setUp()
    {
        $this->asset = $this->getMockForAbstractClass(AbstractAsset::class);
    }

    public function testIsInstanceOfAssetInterface()
    {
        $this->assertInstanceOf(AssetInterface::class, $this->asset);
    }

    public function testIsInstanceOfHtmlElement()
    {
        $this->assertInstanceOf(HtmlElement::class, $this->asset);
    }

    public function testGetId()
    {
        $id = uniqid();
        setAttr($id, 'id', $this->asset);

        $this->assertEquals($id, $this->asset->getId());
    }

    public function testGetDependencies()
    {
        $dependencies = range(0, rand(0, 5));
        setAttr($dependencies, 'dependencies', $this->asset);

        $this->assertEquals($dependencies, $this->asset->getDependencies());
    }

    public function testGetGroups()
    {
        $groups = range(0, rand(0, 5));
        setAttr($groups, 'groups', $this->asset);

        $this->assertEquals($groups, $this->asset->getGroups());
    }

    public function testIsUsedReturnTheUsedAttribute()
    {
        $used = (bool) rand(0, 1);
        setAttr($used, 'used', $this->asset);

        $this->assertEquals($used, $this->asset->isUsed());
    }

    public function testIsNotUsedByDefault()
    {
        $this->assertFalse($this->asset->isUsed());
    }

    public function testHasNotDependenciesByDefault()
    {
        $this->assertEmpty($this->asset->getDependencies());
    }

    public function testAddGroupInsertTheGroupInTheGroupsArray()
    {
        $group = uniqid();
        $this->asset->addGroup($group);

        $this->assertAttributeContains($group, 'groups', $this->asset);
    }

    public function testHasNotGroupsByDefault()
    {
        $this->assertEmpty($this->asset->getGroups());
    }

    public function testHasGroupReturnFalseWhenAssetHasNotTheGroup()
    {
        $group = uniqid();
        $this->assertFalse($this->asset->hasGroup($group));
    }

    public function testHasGroupReturnTrueWhenGroupsAttributeContainsTheGroup()
    {
        $group = uniqid();
        setAttr([$group], 'groups', $this->asset);

        $this->assertTrue($this->asset->hasGroup($group));
    }

    public function testDeleteGroupDeleteTheGroupFromGroupsAttribute()
    {
        $group = uniqid();
        setAttr([$group], 'groups', $this->asset);

        $this->asset->deleteGroup($group);

        $this->assertAttributeNotContains($group, 'groups', $this->asset);
    }

    public function addTwoGroups()
    {
        $this->group1 = uniqid();
        $this->group2 = uniqid();

        $this->asset->addGroup("{$this->group1} {$this->group2}");
    }

    public function testAddGroupCanInsertSeveralGroups()
    {
        $this->addTwoGroups();

        $this->assertTrue($this->asset->hasGroup($this->group1));
        $this->assertTrue($this->asset->hasGroup($this->group2));
    }

    public function testHasGroupReturnFalseIfAtLeastOneGroupIsNotInAsset()
    {
        $this->addTwoGroups();

        $group3 = uniqid();

        $this->assertFalse(
            $this->asset->hasGroup("$group3 {$this->group2}")
        );
    }

    public function testDeleteGroupCanDeleteSeveralGroups()
    {
        $this->addTwoGroups();

        $this->asset->deleteGroup("{$this->group1} {$this->group2}");

        $this->assertFalse($this->asset->hasGroup($this->group1));
        $this->assertFalse($this->asset->hasGroup($this->group2));
    }
}
