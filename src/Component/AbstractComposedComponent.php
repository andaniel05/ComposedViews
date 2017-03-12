<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\Traits\CloningTrait;

abstract class AbstractComposedComponent extends AbstractComponent implements ComponentContainerInterface
{
    use ComponentContainerTrait, CloningTrait;
}