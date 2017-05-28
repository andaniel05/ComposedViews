<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\{AbstractPage, HtmlInterface};
use PlatformPHP\ComposedViews\Asset\AssetsTrait;
use PlatformPHP\ComposedViews\Traits\{PrintTrait, PageTrait};

abstract class AbstractComponent implements HtmlInterface
{
    use AssetsTrait, PrintTrait, PageTrait;

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

    public function detach() : void
    {
        if ($this->parent) {
            $this->parent->dropComponent($this->id);
        }
    }
}