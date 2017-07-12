<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\Traits\CloningTrait;

abstract class AbstractSuperComponent extends AbstractComponent implements ComponentContainerInterface
{
    use ComponentContainerTrait, CloningTrait;
}