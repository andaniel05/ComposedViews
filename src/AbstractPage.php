<?php

namespace PlatformPHP\ComposedViews;

use PlatformPHP\ComposedViews\Asset\AssetsTrait;
use PlatformPHP\ComposedViews\Traits\PrintTrait;

abstract class AbstractPage implements RenderInterface
{
    use AssetsTrait, PrintTrait;

    protected $vars = [];

    public function __construct()
    {
        $this->initializeVars();
        $this->initializeAssets();
    }

    protected function initializeVars() : void
    {
        $this->vars = $this->vars();
    }

    protected function vars() : array
    {
        return [];
    }

    public function getVars() : array
    {
        return $this->vars;
    }

    public function getVar($var)
    {
        return $this->vars[$var] ?? null;
    }

    public function setVar($var, $value)
    {
        if (isset($this->vars[$var])) {
            $this->vars[$var] = $value;
        }
    }

    public function printVar($var) : void
    {
        echo $this->vars[$var] ?? null;
    }
}