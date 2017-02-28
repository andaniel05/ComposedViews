<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\RenderInterface;
use PlatformPHP\ComposedViews\Traits\PrintTrait;

abstract class AbstractComponent implements RenderInterface
{
    use PrintTrait;

    protected $id;
    protected $parent;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setParent(AbstractComponent $parent) : void
    {
        $this->parent = $parent;
    }

    public function getParent() : ?AbstractComponent
    {
        return $this->parent;
    }

    public function getAssets() : array
    {
        return [];
    }
}