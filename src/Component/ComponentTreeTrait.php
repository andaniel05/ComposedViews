<?php

namespace Andaniel05\ComposedViews\Component;

trait ComponentTreeTrait
{
    protected $components = [];

    public function traverse(): iterable
    {
        $generator = function (array $components) use (&$generator) {
            foreach ($components as $component) {
                yield $component;
                yield from $generator($component->getChildren());
            }

            return;
        };

        return $generator($this->components);
    }

    public function getComponent(string $id): ?ComponentInterface
    {
        $idList = preg_split('/\s+/', $id);

        if (1 == count($idList)) {
            foreach ($this->traverse() as $component) {
                if ($id == $component->getId()) {
                    return $component;
                }
            }

            return null;
        }

        $hash = array_fill_keys($idList, null);

        $container = $this;
        for ($i = 0; $i < count($idList); $i++) {
            $componentId = $idList[$i];
            $component = $container->getComponent($componentId);

            if ($component) {
                $hash[$componentId] = $component;
                $container = $component;
            } else {
                break;
            }
        }

        return array_pop($hash);
    }
}
