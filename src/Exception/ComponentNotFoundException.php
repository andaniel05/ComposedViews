<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews\Exception;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class ComponentNotFoundException extends ComposedViewsException
{
    public function __construct(string $componentId)
    {
        parent::__construct("Component '$componentId' not found.");
    }
}
