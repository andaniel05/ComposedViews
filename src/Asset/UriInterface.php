<?php

namespace Andaniel05\ComposedViews\Asset;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
interface UriInterface
{
    public function getUri(): string;

    public function setUri(string $uri);
}
