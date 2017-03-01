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
        $this->initialize();

        if ($this->components instanceOf ComponentCollection) {
            return clone $this->components;
        } else {
            return new ComponentCollection();
        }
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