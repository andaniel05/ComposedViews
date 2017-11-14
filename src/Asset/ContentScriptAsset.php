<?php

namespace Andaniel05\ComposedViews\Asset;

class ContentScriptAsset extends AbstractAsset
{
    public function __construct(string $id, $content, string $dependencies = '', string $groups = '')
    {
        $this->id = $id;
        $this->addDependency($dependencies);
        $this->addGroup("scripts content {$groups}");

        $content = is_array($content) ? $content : [$content];
        parent::__construct('script', [], $content);
    }
}
