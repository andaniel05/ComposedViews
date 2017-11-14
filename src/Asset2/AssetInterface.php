<?php

namespace Andaniel05\ComposedViews\Asset2;

interface AssetInterface
{
    public function getId(): string;

    public function getGroups(): array;

    public function setGroups(array $groups);

    public function addGroup(string $group);

    public function hasGroup(string $group): bool;

    public function deleteGroup(string $group);

    public function getDependencies(): array;

    public function setDependencies(array $dependencies);

    public function addDependency(string $dependency);

    public function hasDependency(string $dependency): bool;

    public function deleteDependency(string $dependency);

    public function isUsed(): bool;

    public function setUsed(bool $used);
}
