<?php

namespace Andaniel05\ComposedViews;

use Andaniel05\ComposedViews\Event\FilterAssetsEvent;
use Andaniel05\ComposedViews\HtmlElement\HtmlInterface;
use Andaniel05\ComposedViews\Asset\{AssetsTrait, AbstractAsset};
use Andaniel05\ComposedViews\Traits\CloningTrait;
use Andaniel05\ComposedViews\Exception\{AssetNotFoundException, ComponentNotFoundException};
use Andaniel05\ComposedViews\Component\{AbstractComponent, Sidebar};
use Symfony\Component\EventDispatcher\{EventDispatcherInterface, EventDispatcher};

abstract class AbstractPage implements HtmlInterface
{
    use CloningTrait;
    use AssetsTrait {
        getAssets as getPageAssets;
    }

    protected $vars = [];
    protected $sidebars = [];
    protected $baseUrl = '';
    protected $pageAssets = [];
    protected $dispatcher;
    protected $printed = false;

    public function __construct(string $baseUrl = '', EventDispatcherInterface $dispatcher = null)
    {
        $this->initializeVars();
        $this->initializeAssets();
        $this->initializeSidebars();

        $this->baseUrl = $baseUrl;
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

    protected function sidebars(): array
    {
        return [];
    }

    public function getAllSidebars(): array
    {
        return $this->sidebars;
    }

    public function getSidebar(string $id): ?Sidebar
    {
        return $this->sidebars[$id] ?? null;
    }

    public function getComponent(string $id): ?AbstractComponent
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

    protected function getComponentFromAllSidebars(string $id): ?AbstractComponent
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

    protected function getAssetsFromComponents(array $components): array
    {
        $assets = [];

        foreach ($components as $component) {

            if ($component instanceOf AbstractComponent) {
                $assets = array_merge(
                    $assets,
                    $this->getAssetsFromComponents($component->getAllComponents())
                );
            }

            $assets = array_merge($assets, $component->getAssets());
        }

        return $assets;
    }

    public function getAllAssets(): array
    {
        $assets = $this->getPageAssets();

        foreach ($this->sidebars as $sidebar) {
            $assets = array_merge(
                $assets,
                $this->getAssetsFromComponents($sidebar->getAllComponents())
            );
        }

        $assets = array_merge($assets, $this->pageAssets);

        return $assets;
    }

    public function getAsset(string $id): ?AbstractAsset
    {
        return $this->getAllAssets()[$id] ?? null;
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

    public function __get(string $name): ?AbstractComponent
    {
        return $this->getComponent($name);
    }

    public function components(): iterable
    {
        $generator = function (array $sidebars)
        {
            $gen = function (array $components) use (&$gen)
            {
                foreach ($components as $component) {
                    yield $component;
                    if ($component instanceOf AbstractComponent) {
                        yield from $gen($component->getAllComponents());
                    }
                }
            };

            foreach ($sidebars as $sidebar) {
                yield from $gen($sidebar->getAllComponents());
            }
        };

        return $generator($this->sidebars);
    }

    public function baseUrl(string $assetUrl = ''): string
    {
        return $this->baseUrl . $assetUrl;
    }

    public function addAsset(AbstractAsset $asset): void
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

    public function appendComponent(string $parentId, AbstractComponent $component): void
    {
        $parent = $this->sidebars[$parentId] ?? $this->getComponent($parentId);

        if ( ! $parent) {
            throw new ComponentNotFoundException($parentId);
        }

        $parent->addComponent($component);
        $component->setPage($this);
    }

    public function on(string $eventName, callable $callback): void
    {
        $this->dispatcher->addListener($eventName, $callback);
    }
}