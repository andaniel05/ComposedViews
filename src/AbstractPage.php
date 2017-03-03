<?php

namespace PlatformPHP\ComposedViews;

abstract class AbstractPage
{
    protected $vars;

    public function __construct()
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