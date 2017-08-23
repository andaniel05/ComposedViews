<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset2\Asset2;

class Asset2Test extends TestCase
{
    public function setUp(string $id = null)
    {
        $id = $id ?? uniqid();

        $this->asset = new Asset2($id);
    }

    public function testGetId_ReturnTheIdArgument()
    {
        $id = uniqid();

        $asset = new Asset2($id);

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

    public function testGetMinimizedContent_ReturnValueOfTheContentWhenHisMinimizedContentIsNull()
    {
        $content = uniqid();
        $this->asset->setContent($content);

        $this->assertEquals($content, $this->asset->getMinimizedContent());
    }

    public function testGetMinimizedContent_ReturnInsertedValueBySetMinimizedContent()
    {
        $content = uniqid();
        $this->asset->setMinimizedContent($content);

        $this->assertEquals($content, $this->asset->getMinimizedContent());
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
}