<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\RenderInterface;
use PlatformPHP\ComposedViews\Asset\AssetsTrait;
use PlatformPHP\ComposedViews\Traits\PrintTrait;

Abstract class AbstractComponent implements RenderInterface
{
    use AssetsTrait, PrintTrait;

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

    public function getParent() : ?ComponentContainerInterface
    {
        return $this->parent;
    }

    public function setParent(?ComponentContainerInterface $parent)
    {
        $this->parent = $parent;
    }
}