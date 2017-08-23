<?php

namespace PlatformPHP\ComposedViews\Asset2;

class Asset2
{
    protected $id;
    protected $groups = [];

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
}