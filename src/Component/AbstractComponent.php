<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\{AbstractPage, RenderInterface};
use PlatformPHP\ComposedViews\Asset\AssetsTrait;
use PlatformPHP\ComposedViews\Traits\PrintTrait;

Abstract class AbstractComponent implements RenderInterface
{
    use AssetsTrait, PrintTrait;

    protected $id;
    protected $parent;
    protected $page;

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

    public function detach() : void
    {
        if ($this->parent) {
            $this->parent->dropComponent($this->id);
        }
    }

    public function getPage() : ?AbstractPage
    {
        return $this->page;
    }

    public function setPage(?AbstractPage $page)
    {
        $this->page = $page;
    }
}