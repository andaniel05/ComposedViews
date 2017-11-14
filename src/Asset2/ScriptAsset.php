<?php

namespace Andaniel05\ComposedViews\Asset2;

class ScriptAsset extends AbstractAsset implements UriInterface
{
    public function __construct(string $id, string $uri, string $deps = '')
    {
        $this->id = $id;
        $this->addDependency($deps);
        $this->addGroup('scripts uri');

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
