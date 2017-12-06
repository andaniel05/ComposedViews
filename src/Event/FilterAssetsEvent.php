<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class FilterAssetsEvent extends Event
{
    protected $assets;

    public function __construct(array $assets)
    {
        $this->assets = $assets;
    }

    public function getAssets(): array
    {
        return $this->assets;
    }

    public function setAssets(array $assets)
    {
        $this->assets = $assets;
    }
}
