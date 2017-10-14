<?php

namespace Andaniel05\ComposedViews\Component;

trait ComponentTreeTrait
{
    protected $components = [];

    public function traverse(): iterable
    {
        $generator = function (array $components) use (&$generator)
        {
            foreach ($components as $component) {
                yield $component;
                yield from $generator($component->getChildren());
            }

            return;
        };

        return $generator($this->components);
    }
}