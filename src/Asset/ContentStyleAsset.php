<?php

namespace Andaniel05\ComposedViews\Asset;

class ContentStyleAsset extends AbstractAsset
{
    public function __construct(string $id, $content, string $dependencies = '', string $groups = '')
    {
        $this->id = $id;
        $this->addDependency($dependencies);
        $this->addGroup("styles content {$groups}");

        $content = is_array($content) ? $content : [$content];
        parent::__construct('style', [], $content);
    }
}
