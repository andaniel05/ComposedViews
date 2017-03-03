<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\RenderInterface;
use PlatformPHP\ComposedViews\Asset\AssetCollection;
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

    public function setParent(ComponentContainerInterface $parent) : void
    {
        $this->parent = $parent;
    }

    public function getParent() : ?ComponentContainerInterface
    {
        return $this->parent;
    }

    public function getAssets() : AssetCollection
    {
        return new AssetCollection();
    }
}