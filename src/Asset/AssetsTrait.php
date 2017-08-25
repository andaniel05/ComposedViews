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
        $loadAssets = function (array $assets, string $groups = '') use (&$loadAssets) {
            foreach ($assets as $key => $value) {
                if ($value instanceOf AbstractAsset) {

                    $asset = $value;

                    if ( ! empty($groups)) {
                        $asset->addGroups($groups);
                    }

                    $this->assets[$asset->getId()] = $asset;

                } elseif (is_string($key) && is_array($value)) {
                    $loadAssets($value, $groups . " $key");
                }
            }
        };

        $loadAssets($this->assets());
    }
}