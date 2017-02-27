<?php

namespace PlatformPHP\ComposedViews\Traits;

trait PrintTrait
{
    public function print() : void
    {
        echo $this->render();
    }
}