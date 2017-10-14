<?php

namespace Andaniel05\ComposedViews\Component;

use Andaniel05\ComposedViews\PageInterface;
use Andaniel05\ComposedViews\HtmlElement\HtmlInterface;

interface ComponentInterface extends HtmlInterface
{
    public function getAssets(): array;

    public function clone();

    public function traverse(): iterable;

    public function getComponent(string $id): ?ComponentInterface;

    public function getId(): string;

    public function getParent(): ?ComponentInterface;

    public function setParent(?ComponentInterface $parent);

    public function detach(): void;

    public function renderizeChildren(): ?string;

    public function getChildren(): array;

    public function addChild(ComponentInterface $component);

    public function dropChild(string $id, bool $notifyChild = true);

    public function hasRootChild(string $id): bool;

    public function getPage(): ?PageInterface;

    public function setPage(?PageInterface $page);
}