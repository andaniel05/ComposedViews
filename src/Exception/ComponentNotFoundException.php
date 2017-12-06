<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews\Exception;

class ComponentNotFoundException extends ComposedViewsException
{
    public function __construct(string $componentId)
    {
        parent::__construct("Component '$componentId' not found.");
    }
}
