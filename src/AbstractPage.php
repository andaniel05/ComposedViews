<?php

namespace PlatformPHP\ComposedViews;

use PlatformPHP\ComposedViews\Asset\AssetsTrait;
use PlatformPHP\ComposedViews\Traits\PrintTrait;
use PlatformPHP\ComposedViews\Sidebar\Sidebar;
use PlatformPHP\ComposedViews\Component\AbstractComponent;

abstract class AbstractPage implements RenderInterface
{
    use AssetsTrait, PrintTrait;

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

    public function getVars() : array
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

            if (is_int($key) && is_string($value)) {
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
            $component = $this->getComponentInAllSidebars($id);
        } else {
            $sidebar = $this->getSidebar($idList[0]);
            if ($sidebar) {
                $componentId = preg_split("/{$idList[0]}\s+/", $id)[1];
                $component = $sidebar->getComponent($componentId);
            } else {
                $component = $this->getComponentInAllSidebars($id);
            }
        }

        return $component;
    }

    protected function getComponentInAllSidebars(string $id) : ?AbstractComponent
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
}