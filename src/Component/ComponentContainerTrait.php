<?php

namespace PlatformPHP\ComposedViews\Component;

trait ComponentContainerTrait
{
    protected $components = [];

    public function getAllComponents() : array
    {
        return $this->components;
    }

    public function getComponent(string $id) : ?AbstractComponent
    {
        return $this->components[$id] ?? null;
    }

    public function addComponent(AbstractComponent $component)
    {
        $this->components[$component->getId()] = $component;
    }

    public function dropComponent(string $id)
    {
        unset($this->components[$id]);
    }

    public function existsComponent(string $id) : bool
    {
        return isset($this->components[$id]);
    }
}