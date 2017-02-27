<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\RenderInterface;

abstract class AbstractComponent implements RenderInterface
{
    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId() : string
    {
        return $this->id;
    }
}