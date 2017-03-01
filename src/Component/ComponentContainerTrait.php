<?php

namespace PlatformPHP\ComposedViews\Component;

trait ComponentContainerTrait
{
    protected $components;

    public function initialize() : void
    {
        $this->components = new ComponentCollection();
    }

    public function isInitialized() : bool
    {
        return $this->components instanceOf ComponentCollection;
    }

    public function getAllComponents() : ComponentCollection
    {
        if ( ! $this->isInitialized()) {
            $this->initialize();
        }

        return new ComponentCollection();
    }
}