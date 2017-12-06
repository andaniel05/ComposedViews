<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews\Asset;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class ScriptAsset extends AbstractAsset implements UriInterface
{
    public function __construct(string $id, string $uri, string $dependencies = '', string $groups = '')
    {
        parent::__construct($id);

        if ($dependencies) {
            $this->addDependency($dependencies);
        }

        $this->addGroup("scripts uri {$groups}");
        $this->setTag('script');
        $this->setAttribute('src', $uri);
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
