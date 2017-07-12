<?php

namespace PlatformPHP\ComposedViews\Component;

interface ComponentContainerInterface
{
    public function getAllComponents(): array;

    public function getComponent(string $id): ?AbstractComponent;

    public function addComponent(AbstractComponent $component);

    public function dropComponent(string $id);

    public function existsComponent(string $id): bool;
}