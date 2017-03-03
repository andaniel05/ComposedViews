<?php

namespace PlatformPHP\ComposedViews;

use PlatformPHP\ComposedViews\Asset\AssetsTrait;

abstract class AbstractPage
{
    use AssetsTrait;

    protected $vars;

    public function __construct()
    {
        $this->vars = $this->vars();

        $this->initializeAssets();
    }

    protected function vars() : array
    {
        return [];
    }

    public function getVars() : array
    {
        return $this->vars;
    }

    public function getVar(string $var)
    {
        return $this->vars[$var] ?? null;
    }

    public function setVar(string $var, $value)
    {
        if (isset($this->vars[$var])) {
            $this->vars[$var] = $value;
        }
    }

    public function printVar(string $var)
    {
        if (isset($this->vars[$var])) {
            echo $this->vars[$var];
        }
    }
}