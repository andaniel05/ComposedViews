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

        return $this->components ?
            clone $this->components : new ComponentCollection();
    }

    public function addComponent(string $id, AbstractComponent $component) : void
    {
        if ( ! $this->isInitialized()) {
            $this->initialize();
        }

        $this->components[$id] = $component;
    }

    public function getComponent(string $id) : ?AbstractComponent
    {
        return isset($this->components[$id]) ? $this->components[$id] : null;
    }
}