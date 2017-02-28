<?php

namespace PlatformPHP\ComposedViews\Component;

trait ComponentContainerTrait
{
    protected $components = null;

    public function getAllComponents() : ?ComponentCollection
    {
        return $this->components;
    }

    public function getComponent() : ?AbstractComponent
    {
        return null;
    }

    public function insertComponent(string $id, AbstractComponent $component) : void
    {
        if ( ! $this->components) {
            $this->components = new ComponentCollection();
        }

        $this->components[$id] = $component;
    }
}