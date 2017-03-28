<?php

namespace PlatformPHP\ComposedViews\Exception;

class AssetNotFoundException extends \Exception
{
    public function __construct(string $assetId, string $dependency)
    {
        parent::__construct("El asset $assetId depende del asset $dependency que no se encuentra.");
    }
}