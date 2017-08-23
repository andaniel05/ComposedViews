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
}