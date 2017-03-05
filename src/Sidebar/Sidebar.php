<?php

namespace PlatformPHP\ComposedViews\Sidebar;

use PlatformPHP\ComposedViews\RenderInterface;
use PlatformPHP\ComposedViews\Traits\PrintTrait;

class Sidebar implements RenderInterface
{
    use PrintTrait;

    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getComponents() : array
    {
        return [];
    }

    public function render() : string
    {
        $result = '';

        foreach ($this->getComponents() as $component) {
            $result .= $component->render();
        }

        return $result;
    }
}