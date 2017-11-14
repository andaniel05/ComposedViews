<?php

namespace Andaniel05\ComposedViews\Asset2;

class StyleAsset extends AbstractAsset implements UriInterface
{
    public function __construct(string $id, string $uri, string $dependencies = '', string $groups = '')
    {
        $this->id = $id;
        $this->addDependency($dependencies);
        $this->addGroup("styles uri {$groups}");

        parent::__construct('link', ['href' => $uri], [], false);
    }

    public function getUri(): string
    {
        return $this->getAttribute('href');
    }

    public function setUri(string $uri)
    {
        $this->setAttribute('href', $uri);
    }
}
