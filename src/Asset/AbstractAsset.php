<?php

namespace Andaniel05\ComposedViews\Asset;

use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

abstract class AbstractAsset extends HtmlElement implements AssetInterface
{
    protected $id;
    protected $dependencies = [];
    protected $groups = [];
    protected $used = false;

    public function __construct(string $id, array $dependencies = [], array $groups = [])
    {
        parent::__construct();

        $this->id = $id;
        $this->dependencies = $dependencies;
        $this->groups = $groups;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function setGroups(array $groups)
    {
        $this->groups = $groups;
    }

    public function hasGroup(string $group): bool
    {
        $groups = explode(' ', $group);
        $result = true;

        foreach ($groups as $g) {
            if (! in_array($g, $this->groups)) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    public function addGroup(string $group)
    {
        $groups = explode(' ', trim($group));
        $this->groups = array_merge($this->groups, $groups);
    }

    public function deleteGroup(string $group)
    {
        $groups = explode(' ', $group);
        foreach ($groups as $group) {
            $id = array_search($group, $this->groups);
            if (false !== $id) {
                unset($this->groups[$id]);
            }
        }
    }

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function setUsed(bool $used)
    {
        $this->used = $used;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function setDependencies(array $dependencies)
    {
        $this->dependencies = $dependencies;
    }

    public function addDependency(string $dependency)
    {
        $deps = explode(' ', $dependency);
        $this->dependencies = array_merge($this->dependencies, $deps);
    }

    public function hasDependency(string $dependency): bool
    {
        $deps = explode(' ', $dependency);
        $result = true;

        foreach ($deps as $dep) {
            if (! in_array($dep, $this->dependencies)) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    public function deleteDependency(string $dependency)
    {
        $deps = explode(' ', $dependency);
        foreach ($deps as $dep) {
            $id = array_search($dep, $this->dependencies);
            if (false !== $id) {
                unset($this->dependencies[$id]);
            }
        }
    }
}
