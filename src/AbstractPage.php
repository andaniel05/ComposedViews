<?php

namespace Andaniel05\ComposedViews;

use Andaniel05\ComposedViews\Event\FilterAssetsEvent;
use Andaniel05\ComposedViews\Asset\{AssetsTrait, AssetInterface};
use Andaniel05\ComposedViews\Traits\CloningTrait;
use Andaniel05\ComposedViews\Exception\{AssetNotFoundException, ComponentNotFoundException};
use Andaniel05\ComposedViews\Component\{ComponentInterface, Sidebar, ComponentTreeTrait};
use Symfony\Component\EventDispatcher\{EventDispatcherInterface, EventDispatcher};

abstract class AbstractPage implements PageInterface
{
    use CloningTrait;

    use AssetsTrait {
        getAssets as getPageAssets;
    }

    use ComponentTreeTrait {
        traverse as components;
    }

    protected $vars = [];
    protected $basePath = '';
    protected $pageAssets = [];
    protected $dispatcher;
    protected $printed = false;
    protected $title = '';
    protected $lang = 'en';
    protected $charset = 'utf-8';

    public function __construct(string $basePath = '', EventDispatcherInterface $dispatcher = null)
    {
        $this->initializeVars();
        $this->initializeAssets();
        $this->initializeSidebars();

        $this->basePath = $basePath;
        $this->dispatcher = $dispatcher ?? new EventDispatcher();
    }

    protected function initializeVars(): void
    {
        $this->vars = $this->vars();
    }

    protected function vars(): array
    {
        return [];
    }

    public function getAllVars(): array
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

    protected function initializeSidebars(): void
    {
        foreach ($this->sidebars() as $key => $value) {

            $sidebar = null;

            if (is_integer($key) && is_string($value)) {
                $sidebar = new Sidebar($value);
            } elseif (is_string($key) && is_array($value)) {

                $sidebar = new Sidebar($key);
                foreach ($value as $component) {
                    if ($component instanceOf ComponentInterface) {
                        $sidebar->addChild($component);
                    }
                }

            }

            if ($sidebar) {
                $sidebar->setPage($this);
                $this->components[$sidebar->getId()] = $sidebar;
            }
        }
    }

    protected function sidebars(): array
    {
        return [];
    }

    public function getAllSidebars(): array
    {
        return $this->components;
    }

    public function getSidebar(string $id): ?ComponentInterface
    {
        return $this->components[$id] ?? null;
    }

    protected function getAssetsFromComponents(array $components): array
    {
        $assets = [];

        foreach ($components as $component) {

            if ($component instanceOf ComponentInterface) {
                $assets = array_merge(
                    $assets,
                    $this->getAssetsFromComponents($component->getChildren())
                );
            }

            $assets = array_merge($assets, $component->getAssets());
        }

        return $assets;
    }

    public function getAllAssets(): array
    {
        $assets = $this->getPageAssets();

        foreach ($this->components as $sidebar) {
            $assets = array_merge(
                $assets,
                $this->getAssetsFromComponents($sidebar->getChildren())
            );
        }

        $assets = array_merge($assets, $this->pageAssets);

        return $assets;
    }

    public function getAsset(string $id, bool $markUsage = false): ?AssetInterface
    {
        $asset = $this->getAllAssets()[$id] ?? null;

        if ($asset && $markUsage) {
            $asset->setUsed(true);
        }

        return $asset;
    }

    public function getAssets(?string $group = null, bool $filterUnused = true, bool $markUsage = true): array
    {
        $result = [];
        $assets = $this->getOrderedAssets();

        if ($filterUnused) {
            array_walk($assets, function ($asset, $id) use (&$assets) {
                if ($asset->isUsed()) {
                    unset($assets[$id]);
                }
            });
        }

        if ( ! $group) {
            $result = $assets;
        } else {
            foreach ($assets as $id => $asset) {
                if ($asset->inGroup($group)) {
                    $result[$id] = $asset;
                }
            }
        }

        if ($markUsage) {
            foreach ($result as $id => $asset) {
                $asset->setUsed(true);
            }
        }

        return $result;
    }

    public function renderAssets(?string $group = null, bool $filterUnused = true, bool $markUsage = true): string
    {
        $result = '';

        $assets = $this->getAssets($group, $filterUnused, $markUsage);
        foreach ($assets as $asset) {
            $result .= $asset->html() . PHP_EOL;
        }

        return $result;
    }

    public function getOrderedAssets(): array
    {
        $result = [];
        $assets = $this->getAllAssets();

        $putAssetInOrder = function ($asset) use (&$result, &$assets, &$putAssetInOrder)
        {
            foreach ($asset->getDependencies() as $depId) {
                $dep = $assets[$depId] ?? null;

                if ( ! $dep) {
                    throw new AssetNotFoundException($asset->getId(), $depId);
                }

                if ($dep && ! isset($result[$depId])) {
                    $putAssetInOrder($dep);
                }
            }

            $result[$asset->getId()] = $asset;
        };

        foreach ($assets as $asset) {
            $putAssetInOrder($asset);
        }

        if ($this->dispatcher) {
            $event = new FilterAssetsEvent($result);
            $this->dispatcher
                ->dispatch(PageEvents::FILTER_ASSETS, $event);
            $result = $event->getAssets();
        }

        return $result;
    }

    public function __get(string $name): ?ComponentInterface
    {
        return $this->getComponent($name);
    }

    public function basePath(string $assetUri = ''): string
    {
        return $this->basePath . $assetUri;
    }

    public function addAsset(AssetInterface $asset): void
    {
        $this->pageAssets[$asset->getId()] = $asset;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
    }

    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function print(): void
    {
        echo $this->html();

        $this->printed = true;
    }

    public function isPrinted(): bool
    {
        return $this->printed;
    }

    public function appendComponent(string $parentId, ComponentInterface $component): void
    {
        $parent = $this->components[$parentId] ?? $this->getComponent($parentId);

        if ( ! $parent) {
            throw new ComponentNotFoundException($parentId);
        }

        $parent->addChild($component);
        $component->setPage($this);
    }

    public function on(string $eventName, callable $callback): void
    {
        $this->dispatcher->addListener($eventName, $callback);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang)
    {
        $this->lang = $lang;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function setCharset(string $charset)
    {
        $this->charset = $charset;
    }
}
