<?php

namespace PlatformPHP\ComposedViews\Component;

use Andaniel05\ObjectCollection\ObjectCollection;

class ComponentCollection extends ObjectCollection
{
    public function __construct()
    {
        parent::__construct(AbstractComponent::class);
    }
}