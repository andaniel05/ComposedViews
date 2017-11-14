<?php

namespace Andaniel05\ComposedViews\Asset;

class ContentStyleAsset extends AbstractAsset
{
    public function __construct(string $id, $content, string $dependencies = '', string $groups = '')
    {
        parent::__construct($id);

        if ($dependencies) {
            $this->addDependency($dependencies);
        }

        $this->addGroup("styles content {$groups}");
        $this->setTag('style');

        $content = is_array($content) ? $content : [$content];
        $this->content = $content;
    }
}
