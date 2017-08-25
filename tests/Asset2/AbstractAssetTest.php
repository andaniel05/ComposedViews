<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\AbstractPage;
use PlatformPHP\ComposedViews\Asset2\AbstractAsset;

class AbstractAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = $this->getMockForAbstractClass(AbstractAsset::class, [$id]);
    }

    public function testConstructor()
    {
        $id = uniqid();
        $groups = range(0, rand(0, 10));
        $deps = range(0, rand(0, 10));
        $content = uniqid();

        $asset = $this->getMockForAbstractClass(
            AbstractAsset::class,
            [$id, $groups, $deps, $content]
        );

        $this->assertEquals($id, $asset->getId());
        $this->assertEquals($groups, $asset->getGroups());
        $this->assertEquals($deps, $asset->getDependencies());
        $this->assertEquals($content, $asset->getContent());
    }

    public function testGetId_ReturnTheIdArgument()
    {
        $id = uniqid();

        $asset = $this->getMockForAbstractClass(AbstractAsset::class, [$id]);

        $this->assertEquals($id, $asset->getId());
    }

    public function testGetGroups_ReturnAnEmptyArrayByDefault()
    {
        $this->assertEquals([], $this->asset->getGroups());
    }

    public function testGetGroups_ReturnAnArrayWithAllInsertedGroups()
    {
        $group = uniqid();

        $this->asset->addGroup($group);

        $this->assertContains($group, $this->asset->getGroups());
    }

    public function testInGroup_ReturnFalseIfAssetIsNotInGroup()
    {
        $group = uniqid();

        $this->assertFalse($this->asset->inGroup($group));
    }

    public function testInGroup_ReturnTrueWhenAssetIsInGroup()
    {
        $group = uniqid();

        $this->asset->addGroup($group);

        $this->assertTrue($this->asset->inGroup($group));
    }

    public function testRemoveGroup_RemoveTheGroupFromTheAsset()
    {
        $group = uniqid();
        $this->asset->addGroup($group);

        $this->asset->removeGroup($group);

        $this->assertEquals([], $this->asset->getGroups());
    }

    public function testGetDependencies_ReturnAnEmptyArrayByDefault()
    {
        $this->assertEquals([], $this->asset->getDependencies());
    }

    public function testGetDependencies_ReturnAnArrayWithAllDefinedDependencies()
    {
        $dependency = uniqid();
        $this->asset->addDependency($dependency);

        $this->assertContains($dependency, $this->asset->getDependencies());
    }

    public function testHasDependency_ReturnFalseIfAssetDoNotHasDefinedTheDependency()
    {
        $dependency = uniqid();

        $this->assertFalse($this->asset->hasDependency($dependency));
    }

    public function testHasDependency_ReturnTrueIfAssetHasTheDependency()
    {
        $dependency = uniqid();

        $this->asset->addDependency($dependency);

        $this->assertTrue($this->asset->hasDependency($dependency));
    }

    public function testRemoveDependency()
    {
        $dependency = uniqid();
        $this->asset->addDependency($dependency);

        $this->asset->removeDependency($dependency);

        $this->assertFalse($this->asset->hasDependency($dependency));
    }

    public function testGetContent_ReturnNullByDefault()
    {
        $this->assertNull($this->asset->getContent());
    }

    public function testGetContent_ReturnValueInsertedBySetContent()
    {
        $content = uniqid();
        $this->asset->setContent($content);

        $this->assertEquals($content, $this->asset->getContent());
    }

    public function testIsUsed_ReturnFalseByDefault()
    {
        $this->assertFalse($this->asset->isUsed());
    }

    public function testIsUsed_ReturnInsertedValueBySetUsed()
    {
        $this->asset->setUsed(true);

        $this->assertTrue($this->asset->isUsed());
    }

    public function testGetPage_ReturnNullByDefault()
    {
        $this->assertNull($this->asset->getPage());
    }

    public function testGetPage_ReturnPageInsertedBySetPage()
    {
        $page = $this->createMock(AbstractPage::class);
        $this->asset->setPage($page);

        $this->assertEquals($page, $this->asset->getPage());
    }

    public function testAddGroups_InsertSeveralGroups()
    {
        $group1 = uniqid();
        $group2 = uniqid();

        $this->asset->addGroups("$group1 $group2");

        $this->assertTrue($this->asset->inGroup($group1));
        $this->assertTrue($this->asset->inGroup($group2));
    }

    public function testInGroups_ReturnTrueWhenAssetIsInAllSpecifiedGroups()
    {
        $group1 = uniqid();
        $group2 = uniqid();

        $this->asset->addGroup($group1);
        $this->asset->addGroup($group2);

        $this->assertTrue($this->asset->inGroups("$group1 $group2"));
    }

    public function testInGroups_ReturnFalseIfAssetIsNotInAllSpecifiedGroups()
    {
        $group1 = uniqid();
        $group2 = uniqid();

        $this->asset->addGroup($group1);
        $this->asset->addGroup($group2);

        $this->assertFalse($this->asset->inGroups("$group1 group3"));
    }
}