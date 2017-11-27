<?php

namespace Andaniel05\ComposedViews\Builder;

use Andaniel05\ComposedViews\PageInterface;
use Andaniel05\ComposedViews\Builder\Event\BuilderEvent;
use Andaniel05\ComposedViews\Component\ComponentInterface;

class PageBuilder extends Builder
{
    public function __construct()
    {
        parent::__construct();

        $this->onTag('page', [$this, 'onPageTag']);
        $this->onTagPopulation('page', [$this, 'onPageTagPopulation']);
    }

    public function onPageTag(BuilderEvent $event)
    {
        $element = $event->getXMLElement();
        $pageClass = (string) $element['class'];
        $basePath = (string) $element['base-path'];

        if ( ! class_exists($pageClass)) {
            throw new Exception\InvalidPageClassException;
        }

        $page = new $pageClass($basePath);

        if ( ! $page instanceof PageInterface) {
            throw new Exception\InvalidPageClassException;
        }

        $event->setEntity($page);
    }

    public function onPageTagPopulation(BuilderEvent $event)
    {
        $page = $event->getEntity();
        if ( ! $page instanceOf PageInterface) {
            return;
        }

        $element = $event->getXMLElement();
        foreach ($element->sidebar as $sidebarElement) {
            $sidebarId = (string) $sidebarElement['id'];
            if ($sidebarId) {
                $sidebar = $page->getSidebar($sidebarId);
                if ($sidebar instanceOf ComponentInterface) {
                    foreach ($sidebarElement->children() as $childrenElement) {
                        $child = $this->build($childrenElement->asXML());
                        if ($child instanceOf ComponentInterface) {
                            $sidebar->addChild($child);
                        }
                    }
                }
            }
        }
    }
}
