<?php

namespace PlatformPHP\ComposedViews;

use PlatformPHP\ComposedViews\Asset\{AssetsTrait, AssetInterface};
use PlatformPHP\ComposedViews\Traits\{PrintTrait, CloningTrait};
use PlatformPHP\ComposedViews\Sidebar\Sidebar;
use PlatformPHP\ComposedViews\Component\{AbstractComponent,
    ComponentContainerInterface};

abstract class AbstractPage implements RenderInterface
{
    use PrintTrait, CloningTrait;
    use AssetsTrait {
        getAssets as getPageAssets;
    }

    protected $vars = [];
    protected $sidebars = [];

    public function __construct()
    {
        $this->initializeVars();
        $this->initializeAssets();
        $this->initializeSidebars();
    }

    protected function initializeVars() : void
    {
        $this->vars = $this->vars();
    }

    protected function vars() : array
    {
        return [];
    }

    public function getAllVars() : array
    {
        return $this->vars;
    }

    public function getVar($var)
    {
        return $this->vars[$var] ?? null;
    }

    public function setVar($var, $value)
    {
        if (isset($this->vars[$var])) {
            $this->vars[$var] = $value;
        }
    }

    public function printVar($var) : void
    {
        echo $this->vars[$var] ?? null;
    }

    protected function initializeSidebars() : void
    {
        foreach ($this->sidebars() as $key => $value) {

            $sidebar = null;

            if (is_integer($key) && is_string($value)) {
                $sidebar = new Sidebar($value);
            } elseif (is_string($key) && is_array($value)) {

                $sidebar = new Sidebar($key);
                foreach ($value as $component) {
                    if ($component instanceOf AbstractComponent) {
                        $sidebar->addComponent($component);
                    }
                }

            }

            if ($sidebar) {
                $this->sidebars[$sidebar->getId()] = $sidebar;
            }
        }
    }

    protected function sidebars() : array
    {
        return [];
    }

    public function getAllSidebars() : array
    {
        return $this->sidebars;
    }

    public function getSidebar(string $id) : ?Sidebar
    {
        return $this->sidebars[$id] ?? null;
    }

    public function printSidebar(string $id) : void
    {
        $sidebar = $this->getSidebar($id);
        if ($sidebar) {
            $sidebar->print();
        }
    }

    public function getComponent(string $id) : ?AbstractComponent
    {
        $component = null;

        $idList = preg_split('/\s+/', $id);

        if (1 == count($idList)) {
            $component = $this->getComponentFromAllSidebars($id);
        } else {
            $sidebar = $this->getSidebar($idList[0]);
            if ($sidebar) {
                $componentId = preg_split("/{$idList[0]}\s+/", $id)[1];
                $component = $sidebar->getComponent($componentId);
            } else {
                $component = $this->getComponentFromAllSidebars($id);
            }
        }

        return $component;
    }

    protected function getComponentFromAllSidebars(string $id) : ?AbstractComponent
    {
        $component = null;

        foreach ($this->getAllSidebars() as $sidebar) {
            $component = $sidebar->getComponent($id);
            if ($component) {
                break;
            }
        }

        return $component;
    }

    protected function getAssetsFromComponents(array $components) : array
    {
        $assets = [];

        foreach ($components as $component) {

            if ($component instanceOf ComponentContainerInterface) {
                $assets = array_merge(
                    $assets,
                    $this->getAssetsFromComponents($component->getAllComponents())
                );
            }

            $assets = array_merge($assets, $component->getAssets());
        }

        return $assets;
    }

    public function getAllAssets() : array
    {
        $assets = [];

        foreach ($this->sidebars as $sidebar) {
            $assets = array_merge(
                $assets,
                $this->getAssetsFromComponents($sidebar->getAllComponents())
            );
        }

        return $assets;
    }

    public function getAsset(string $id) : ?AssetInterface
    {
        return $this->getAllAssets()[$id] ?? null;
    }

    public function getAssets(?string $group = null) : array
    {
        $result = [];
        $assets = $this->getOrderedAssets();

        if ( ! $group) {
            return $assets;
        }

        foreach ($assets as $id => $asset) {
            if ($group == $asset->getGroup()) {
                $result[$id] = $asset;
            }
        }

        return $result;
    }

    public function getOrderedAssets() : array
    {
        $result = [];
        $assets = $this->getAllAssets();

        $putAssetInOrder = function ($asset) use (&$result, &$assets, &$putAssetInOrder)
        {
            foreach ($asset->getDependencies() as $depId) {
                $dep = $assets[$depId] ?? null;
                if ($dep && ! isset($result[$depId])) {
                    $putAssetInOrder($dep);
                }
            }

            $result[$asset->getId()] = $asset;
        };

        foreach ($assets as $asset) {
            $putAssetInOrder($asset);
        }

        return $result;
    }
}