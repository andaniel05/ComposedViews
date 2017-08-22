<?php

namespace PlatformPHP\ComposedViews\Exception;

class ComponentNotFoundException extends \Exception
{
    public function __construct(string $componentId)
    {
        parent::__construct("No se ha encontrado el componente de id igual a \"$componentId\"");
    }
}