<?php

namespace PlatformPHP\ComposedViews\Asset2;

class Asset2
{
    protected $id;
    protected $groups = [];
    protected $dependencies = [];
    protected $content;
    protected $minimizedContent;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function addGroup(string $group)
    {
        $this->groups[] = $group;
    }

    public function inGroup(string $group): bool
    {
        return in_array($group, $this->groups);
    }

    public function removeGroup(string $group)
    {
        $id = array_search($group, $this->groups);

        if (false !== $id) {
            unset($this->groups[$id]);
        }
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function addDependency(string $dependency)
    {
        $this->dependencies[] = $dependency;
    }

    public function hasDependency(string $dependency): bool
    {
        return in_array($dependency, $this->dependencies);
    }

    public function removeDependency(string $dependency)
    {
        $id = array_search($dependency, $this->dependencies);

        if (false !== $id) {
            unset($this->dependencies[$id]);
        }
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content)
    {
        $this->content = $content;
    }

    public function getMinimizedContent(): ?string
    {
        return $this->minimizedContent ?? $this->content;
    }

    public function setMinimizedContent(?string $minimized)
    {
        $this->minimizedContent = $minimized;
    }
}