<?php

namespace PlatformPHP\ComposedViews\Sidebar;

use PlatformPHP\ComposedViews\RenderInterface;
use PlatformPHP\ComposedViews\Traits\PrintTrait;
use PlatformPHP\ComposedViews\Component\{ComponentContainerTrait,
    ComponentContainerInterface};

class Sidebar implements RenderInterface, ComponentContainerInterface
{
    use PrintTrait, ComponentContainerTrait;

    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function render() : string
    {
        $result = '';

        foreach ($this->getAllComponents() as $component) {
            $result .= $component->render();
        }

        return $result;
    }
}