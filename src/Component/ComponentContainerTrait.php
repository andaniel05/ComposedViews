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
        return $this->components ? true : false;
    }

    public function getAllComponents() : ComponentCollection
    {
        $this->initialize();

        return clone $this->components;
    }

    public function getComponent() : ?AbstractComponent
    {
        return null;
    }

    public function insertComponent(string $id, AbstractComponent $component) : void
    {
        $this->initialize();

        if ( ! $this->components) {
            $this->components = new ComponentCollection();
        }

        $this->components[$id] = $component;
    }
}