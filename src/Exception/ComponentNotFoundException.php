<?php

namespace Andaniel05\ComposedViews\Exception;

class ComponentNotFoundException extends ComposedViewsException
{
    public function __construct(string $componentId)
    {
        parent::__construct("No se ha encontrado el componente de id igual a \"$componentId\"");
    }
}