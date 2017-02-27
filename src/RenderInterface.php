<?php

namespace PlatformPHP\ComposedViews;

interface RenderInterface
{
    public function render() : ?string;

    public function print() : void;
}