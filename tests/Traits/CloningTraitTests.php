<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PlatformPHP\ComposedViews\Traits\CloningTrait;
use DeepCopy\DeepCopy;

trait CloningTraitTests
{
    public function testCloneMethodReturnNewInstance()
    {
        $instance = $this->getMockBuilder($this->getTestClass())
            ->disableOriginalConstructor();
        $instance = $this->assumeMock($this->getTestClass(), $instance);

        $newInstance = $instance->clone();

        $this->assertNotEquals((array) $instance, (array) $newInstance);
    }
}