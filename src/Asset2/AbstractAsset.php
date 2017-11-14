<?php

namespace Andaniel05\ComposedViews\Asset2;

use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

abstract class AbstractAsset extends HtmlElement implements AssetInterface
{
    protected $id;
    protected $dependencies = [];
    protected $groups = [];
    protected $used = false;

    public function getId(): string
    {
        return $this->id;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function hasGroup(string $group): bool
    {
        $groups = explode(' ', $group);
        $result = true;

        foreach ($groups as $g) {
            if ( ! in_array($g, $this->groups)) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    public function addGroup(string $group)
    {
        $groups = explode(' ', $group);
        $this->groups = array_merge($this->groups, $groups);
    }

    public function deleteGroup(string $group)
    {
        $groups = explode(' ', $group);
        foreach ($groups as $g) {
            $id = array_search($g, $this->groups);
            if (false !== $id) {
                unset($this->groups[$id]);
            }
        }
    }

    public function isUsed(): bool
    {
        return $this->used;
    }
}
