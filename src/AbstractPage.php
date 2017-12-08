<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews;

use Andaniel05\ComposedViews\Event\FilterAssetsEvent;
use Andaniel05\ComposedViews\Asset\AssetsTrait;
use Andaniel05\ComposedViews\Asset\AssetInterface;
use Andaniel05\ComposedViews\Asset\UriInterface;
use Andaniel05\ComposedViews\Traits\CloningTrait;
use Andaniel05\ComposedViews\Exception\AssetNotFoundException;
use Andaniel05\ComposedViews\Exception\ComponentNotFoundException;
use Andaniel05\ComposedViews\Component\ComponentInterface;
use Andaniel05\ComposedViews\Component\Sidebar;
use Andaniel05\ComposedViews\Component\SidebarInterface;
use Andaniel05\ComposedViews\Component\ComponentTreeTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
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

    public function __construct(string $basePath = '', EventDispatcherInterface $dispatcher = null)
    {
        $this->initializeVars();
        $this->initializeAssets();
        $this->initializeSidebars();

        $this->basePath = $basePath;
        $this->dispatcher = $dispatcher ?? new EventDispatcher();
    }

    protected function filterAssetsUri(): array
    {
        return [];
    }

    protected function initializeVars(): void
    {
        $this->vars = $this->vars();
    }

    protected function vars(): array
    {
        return [
            'title'   => '',
            'lang'    => 'en',
            'charset' => 'utf-8',
        ];
    }

    public function getAllVars(): array
    {
        return $this->vars;
    }

    public function getVar($var)
    {
        return $this->vars[$var] ?? null;
    }

    public function var($var)
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
                    if ($component instanceof ComponentInterface) {
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
        return ['body'];
    }

    public function getAllSidebars(): array
    {
        return $this->components;
    }

    public function getSidebar(string $id): ?SidebarInterface
    {
        return $this->components[$id] ?? null;
    }

    protected function getAssetsFromComponents(array $components): array
    {
        $assets = [];

        foreach ($components as $component) {
            if ($component instanceof ComponentInterface) {
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

    public function getAssets(?string $groups = null, bool $filterUnused = true, bool $markUsage = true): array
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

        if (! $groups) {
            $result = $assets;
        } else {
            foreach ($assets as $id => $asset) {
                if ($asset->hasGroup($groups)) {
                    $result[$id] = $asset;
                }
            }
        }

        if ($markUsage) {
            foreach ($result as $id => $asset) {
                $asset->setUsed(true);
            }
        }

        foreach ($this->filterAssetsUri() as $assetId => $uri) {
            $asset = $result[$id] ?? null;
            if ($asset instanceof UriInterface) {
                $asset->setUri($uri);
            }
        }

        return $result;
    }

    public function renderAssets(?string $groups = null, bool $filterUnused = true, bool $markUsage = true): string
    {
        $result = '';

        $assets = $this->getAssets($groups, $filterUnused, $markUsage);
        foreach ($assets as $asset) {
            $result .= $asset->html() . PHP_EOL;
        }

        return $result;
    }

    public function renderSidebar(string $sidebarId): string
    {
        $sidebar = $this->getSidebar($sidebarId);

        if ($sidebar instanceof SidebarInterface) {
            return $sidebar->html();
        } else {
            throw new ComponentNotFoundException($sidebarId);
        }
    }

    public function renderAsset(string $assetId, bool $required = true, bool $markUsage = true): string
    {
        $asset = $this->getAsset($assetId);

        if ($asset instanceof AssetInterface) {
            if ($markUsage) {
                $asset->setUsed(true);
            }

            return $asset->html();
        } else {
            if ($required) {
                throw new AssetNotFoundException($assetId);
            } else {
                return '';
            }
        }
    }

    public function getOrderedAssets(): array
    {
        $result = [];
        $assets = $this->getAllAssets();

        $putAssetInOrder = function ($asset) use (&$result, &$assets, &$putAssetInOrder) {
            foreach ($asset->getDependencies() as $depId) {
                $dep = $assets[$depId] ?? null;

                if (! $dep) {
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

    public function setBasePath(string $basePath)
    {
        $this->basePath = $basePath;
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

        if (! $parent) {
            throw new ComponentNotFoundException($parentId);
        }

        $parent->addChild($component);
        $component->setPage($this);
    }

    public function on(string $eventName, callable $callback): void
    {
        $this->dispatcher->addListener($eventName, $callback);
    }
}
