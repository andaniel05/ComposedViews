<?php

namespace PlatformPHP\ComposedViews\Asset;

trait AssetsTrait
{
    protected $assets = [];

    protected function assets(): array
    {
        return [];
    }

    public function getAssets(): array
    {
        return $this->assets;
    }

    protected function initializeAssets(): void
    {
        foreach ($this->assets() as $key => $value) {
            if (is_string($key) && is_array($value)) {

                $group = $key;
                $defs = $value;

                foreach ($defs as $def) {
                    $id      = $def[0];
                    $url     = $def[1];
                    $deps    = $def[2] ?? [];
                    $content = $def[3] ?? null;

                    $this->assets[$id] = new Asset($id, $group, $url, $deps, $content);
                }

            } elseif (is_integer($key) && $value instanceOf Asset) {
                $asset = $value;
                $this->assets[$asset->getId()] = $asset;
            }
        }
    }
}