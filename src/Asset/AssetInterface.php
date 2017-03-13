<?php

namespace PlatformPHP\ComposedViews\Asset;

interface AssetInterface
{
    public function getId() : string;

    public function getGroup() : string;

    public function getUrl() : string;

    public function setUrl(string $url);

    public function getContent() : ?string;

    public function setContent(?string $content);

    public function getDependencies() : array;

    public function isUsed() : bool;

    public function setUsed(bool $used);
}