<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class TestCase extends PHPUnitTestCase
{
    public function assumeMock(string $mockClass, MockBuilder $builder): MockObject
    {
        $reflectionClass = new \ReflectionClass($mockClass);
        if ($reflectionClass->isTrait()) {
            return $builder->getMockForTrait();
        } elseif ($reflectionClass->isAbstract()) {
            return $builder->getMockForAbstractClass();
        } else {
            return $builder->getMock();
        }
    }
}