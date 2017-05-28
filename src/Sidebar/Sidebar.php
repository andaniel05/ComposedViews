<?php

namespace PlatformPHP\ComposedViews\Sidebar;

use PlatformPHP\ComposedViews\HtmlInterface;
use PlatformPHP\ComposedViews\Traits\PrintTrait;
use PlatformPHP\ComposedViews\Component\{ComponentContainerTrait,
    ComponentContainerInterface};

class Sidebar implements HtmlInterface, ComponentContainerInterface
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

    public function html() : string
    {
        $result = '';

        foreach ($this->getAllComponents() as $component) {
            $result .= $component->html();
        }

        return $result;
    }
}