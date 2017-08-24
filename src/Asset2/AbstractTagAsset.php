<?php

namespace PlatformPHP\ComposedViews\Asset2;

abstract class AbstractTagAsset extends AbstractMinimizedAsset
{
    public function __construct(string $id, array $groups = [], array $dependencies = [], ?string $content = null, ?string $minimizedContent = null)
    {
        parent::__construct($id, $groups, $dependencies, $content, $minimizedContent);

        $this->addGroup('tag');
    }
}