<?php

namespace Andaniel05\ComposedViews\Tests\Asset;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\AbstractAsset;
use Andaniel05\ComposedViews\Asset\AssetInterface;
use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class AbstractAssetTest extends TestCase
{
    public function setUp()
    {
        $this->asset = $this->getMockForAbstractClass(
            AbstractAsset::class,
            [uniqid()]
        );
    }

    public function testIsInstanceOfAssetInterface()
    {
        $this->assertInstanceOf(AssetInterface::class, $this->asset);
    }

    public function testIsInstanceOfHtmlElement()
    {
        $this->assertInstanceOf(HtmlElement::class, $this->asset);
    }

    public function testGetDependenciesReturnDependenciesArgument()
    {
        $dependencies = range(0, rand(0, 5));
        $asset = $this->getMockForAbstractClass(
            AbstractAsset::class,
            [uniqid(), $dependencies]
        );

        $this->assertEquals($dependencies, $asset->getDependencies());
    }

    public function testGetGroupsReturnGroupsArgument()
    {
        $groups = range(0, rand(0, 5));
        $asset = $this->getMockForAbstractClass(
            AbstractAsset::class,
            [uniqid(), [], $groups]
        );

        $this->assertEquals($groups, $asset->getGroups());
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

    public function testSetUsed()
    {
        $used = (bool) rand(0, 1);
        $this->asset->setUsed($used);

        $this->assertAttributeEquals($used, 'used', $this->asset);
    }

    public function testHasNotDependenciesByDefault()
    {
        $this->assertEquals([], $this->asset->getDependencies());
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

    public function testHasGroupReturnTrueIfCheckedGroupsAreInTheArray()
    {
        $this->addTwoGroups();

        $this->assertTrue(
            $this->asset->hasGroup("{$this->group1} {$this->group2}")
        );
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

    public function testAddDependencyInsertTheDepencendyInTheDependenciesArray()
    {
        $dependency = uniqid();
        $this->asset->addDependency($dependency);

        $this->assertAttributeContains($dependency, 'dependencies', $this->asset);
    }

    public function testHasDependencyReturnFalseWhenDependencyNotExists()
    {
        $this->assertFalse($this->asset->hasDependency(uniqid()));
    }

    public function testHasDependencyReturnTrueWhenDependencyIsInDependenciesArray()
    {
        $dep = uniqid();
        setAttr([$dep], 'dependencies', $this->asset);

        $this->assertTrue($this->asset->hasDependency($dep));
    }

    public function testDeleteDependencyDeleteTheValueFromDependencyArray()
    {
        $dep = uniqid();
        setAttr([$dep], 'dependencies', $this->asset);

        $this->asset->deleteDependency($dep);

        $this->assertFalse($this->asset->hasDependency($dep));
    }

    public function testAddDepencencyCanAddSeveralDependencies()
    {
        $dep1 = uniqid();
        $dep2 = uniqid();

        $this->asset->addDependency("{$dep1} {$dep2}");

        $this->assertTrue($this->asset->hasDependency($dep1));
        $this->assertTrue($this->asset->hasDependency($dep2));
    }

    public function addTwoDependencies()
    {
        $this->dep1 = uniqid();
        $this->dep2 = uniqid();

        $this->asset->addDependency("{$this->dep1} {$this->dep2}");
    }

    public function testHasDependencyReturnTrueIfAllCheckedDepsAreRegistered()
    {
        $this->addTwoDependencies();

        $this->assertTrue(
            $this->asset->hasDependency("{$this->dep1} {$this->dep2}")
        );
    }

    public function testHasDependencyReturnFalseIfAtLeatOneDepIsNotRegistered()
    {
        $this->addTwoDependencies();

        $dep3 = uniqid();

        $this->assertFalse(
            $this->asset->hasDependency("$dep3 {$this->dep2}")
        );
    }

    public function testDeleteDependencyCanDeleteSeveralDependencies()
    {
        $this->addTwoDependencies();

        $this->asset->deleteDependency("{$this->dep1} {$this->dep2}");

        $this->assertEquals([], $this->asset->getDependencies());
    }

    public function testSetGroupsChangeTheGroupsAttribute()
    {
        $groups = range(0, rand(0, 5));
        $this->asset->setGroups($groups);

        $this->assertEquals($groups, $this->asset->getGroups());
    }

    public function testSetDependenciesChangeTheDependenciesAttribute()
    {
        $deps = range(0, rand(0, 5));
        $this->asset->setDependencies($deps);

        $this->assertEquals($deps, $this->asset->getDependencies());
    }
}
