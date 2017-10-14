<?php

namespace Andaniel05\ComposedViews\Asset;

use Andaniel05\ComposedViews\PageInterface;
use Andaniel05\ComposedViews\HtmlElement\{HtmlInterface, HtmlElementInterface};

interface AssetInterface extends HtmlInterface
{
    public function getId(): string;

    public function getGroups(): array;

    public function addGroup(string $group);

    public function addGroups(string $groups);

    public function inGroup(string $group): bool;

    public function inGroups(string $groups): bool;

    public function removeGroup(string $group);

    public function getDependencies(): array;

    public function addDependency(string $dependency);

    public function hasDependency(string $dependency): bool;

    public function removeDependency(string $dependency);

    public function getContent(): ?string;

    public function setContent(?string $content);

    public function isUsed(): bool;

    public function setUsed(bool $used);

    public function getPage(): ?PageInterface;

    public function setPage(?PageInterface $page);

    public function getHtmlElement(): HtmlElementInterface;

    public function setHtmlElement(HtmlElementInterface $element);

    public function updateHtmlElement();
}