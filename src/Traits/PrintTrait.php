<?php

namespace PlatformPHP\ComposedViews\Traits;

trait PrintTrait
{
    protected $printed = false;

    public function print() : void
    {
        echo $this->render();

        $this->printed = true;
    }

    public function isPrinted()
    {
        return $this->printed;
    }
}