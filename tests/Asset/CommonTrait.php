<?php

namespace Andaniel05\ComposedViews\Tests\Asset;

trait CommonTrait
{
    public function setUp()
    {
        $this->asset = $this->newInstance();
    }

    public function testGetIdReturnTheIdArgument()
    {
        $id = uniqid();
        $asset = $this->newInstance(['id' => $id]);

        $this->assertEquals($id, $asset->getId());
    }

    public function testSetDependenciesFromDepsArgument()
    {
        $dep1 = uniqid();

        $asset = $this->newInstance(['deps' => $dep1]);

        $this->assertTrue($asset->hasDependency($dep1));
    }

    public function testInsertGroupsFromGroupsArgument()
    {
        $groups = uniqid();
        $asset = $this->newInstance(['groups' => $groups]);

        $this->assertTrue($asset->hasGroup($groups));
    }
}
