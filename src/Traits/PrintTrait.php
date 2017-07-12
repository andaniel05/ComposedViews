<?php

namespace PlatformPHP\ComposedViews\Traits;

trait PrintTrait
{
    protected $printed = false;

    public function print(): void
    {
        echo $this->html();

        $this->printed = true;
    }

    public function isPrinted(): bool
    {
        return $this->printed;
    }
}