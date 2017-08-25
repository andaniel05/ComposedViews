<?php

namespace PlatformPHP\ComposedViews\Asset2;

use PlatformPHP\ComposedViews\{AbstractPage, HtmlInterface};

abstract class AbstractAsset implements HtmlInterface
{
    protected $id;
    protected $groups = [];
    protected $dependencies = [];
    protected $content;
    protected $used = false;
    protected $page;

    public function __construct(string $id, array $groups = [], array $dependencies = [], ?string $content = null)
    {
        $this->id = $id;
        $this->groups = $groups;
        $this->dependencies = $dependencies;
        $this->content = $content;
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

    public function addGroups(string $groups)
    {
        $parts = explode(' ', $groups);
        foreach ($parts as $group) {
            $this->addGroup($group);
        }
    }

    public function inGroup(string $group): bool
    {
        return in_array($group, $this->groups);
    }

    public function inGroups(string $groups): bool
    {
        $result = true;

        $parts = explode(' ', $groups);
        foreach ($parts as $group) {
            if ( ! $this->inGroup($group)) {
                $result = false;
                break;
            }
        }

        return $result;
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

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function setUsed(bool $used)
    {
        $this->used = $used;
    }

    public function getPage(): ?AbstractPage
    {
        return $this->page;
    }

    public function setPage(?AbstractPage $page)
    {
        $this->page = $page;
    }

    abstract public function html(): string;
}