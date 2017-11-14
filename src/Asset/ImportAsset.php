<?php

namespace Andaniel05\ComposedViews\Asset;

class ImportAsset extends AbstractAsset implements UriInterface
{
    public function __construct(string $id, string $uri, string $dependencies = '', string $groups = '')
    {
        parent::__construct($id);

        if ($dependencies) {
            $this->addDependency($dependencies);
        }

        $this->addGroup("imports uri {$groups}");
        $this->setTag('link');
        $this->setAttributes(['href' => $uri, 'rel' => 'import']);
        $this->setEndTag(false);
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
