<?php

namespace Andaniel05\ComposedViews\Asset;

interface UriInterface
{
    public function getUri(): string;

    public function setUri(string $uri);
}
