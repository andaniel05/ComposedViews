<?php

namespace PlatformPHP\ComposedViews\Traits;

use DeepCopy\DeepCopy;

trait CloningTrait
{
    public function clone()
    {
        return (new DeepCopy)->copy($this);
    }
}