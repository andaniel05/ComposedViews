<?php

namespace Andaniel05\ComposedViews\Builder;

use Andaniel05\ComposedViews\PageInterface;
use Andaniel05\ComposedViews\Builder\Event\BuilderEvent;

class PageBuilder extends Builder
{
    public function __construct()
    {
        parent::__construct();

        $this->onTag('page', [$this, 'onPageTag']);
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
}
