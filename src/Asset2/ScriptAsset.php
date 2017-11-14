<?php

namespace Andaniel05\ComposedViews\Asset2;

class ScriptAsset extends AbstractAsset implements UriInterface
{
    public function __construct(string $id, string $uri, string $dependencies = '', string $groups = '')
    {
        $this->id = $id;
        $this->addDependency($dependencies);
        $this->addGroup("scripts uri {$groups}");

        parent::__construct('script', ['src' => $uri]);
    }

    public function getUri(): string
    {
        return $this->getAttribute('src');
    }

    public function setUri(string $uri)
    {
        $this->setAttribute('src', $uri);
    }
}
