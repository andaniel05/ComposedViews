<?php

namespace PlatformPHP\ComposedViews\Asset2;

class Asset
{
    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}