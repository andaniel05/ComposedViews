<?php

namespace Andaniel05\ComposedViews;

use Andaniel05\ComposedViews\HtmlElement\HtmlInterface;
use Andaniel05\ComposedViews\Component\ComponentInterface;
use Andaniel05\ComposedViews\Asset\AssetInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface PageInterface extends HtmlInterface
{
    public function clone();

    public function getAssets(): array;

    public function traverse(): iterable;

    public function getComponent(string $id): ?ComponentInterface;

    public function getAllVars(): array;

    public function getVar($var);

    public function setVar($var, $value);

    public function getAllSidebars(): array;

    public function getSidebar(string $id): ?ComponentInterface;

    public function getAllAssets(): array;

    public function getAsset(string $id): ?AssetInterface;

    public function renderAssets(?string $group = null, bool $filterUnused = true, bool $markUsage = true): string;

    public function getOrderedAssets(): array;

    public function basePath(string $assetUri = ''): string;

    public function addAsset(AssetInterface $asset): void;

    public function setDispatcher(EventDispatcherInterface $dispatcher): void;

    public function getDispatcher(): EventDispatcherInterface;

    public function print(): void;

    public function isPrinted(): bool;

    public function appendComponent(string $parentId, ComponentInterface $component): void;

    public function on(string $eventName, callable $callback): void;

    public function getTitle(): string;

    public function setTitle(string $title);

    public function getLang(): string;

    public function setLang(string $lang);
}