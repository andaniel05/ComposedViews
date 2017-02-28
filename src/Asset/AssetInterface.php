<?php

namespace PlatformPHP\ComposedViews\Asset;

interface AssetInterface
{
    public function getId() : string;

    public function getType() : string;

    public function getUrl() : string;

    public function getContent() : ?string;
}