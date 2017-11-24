<?php

namespace Andaniel05\ComposedViews\Builder;

class PageBuilder extends Builder
{
    public function __construct()
    {
        parent::__construct();

        $this->onTag('page', function ($event) {
            $element = $event->getXMLElement();
            $pageClass = (string) $element['class'];
            $basePath = (string) $element['base-path'];

            $page = new $pageClass($basePath);
            $event->setEntity($page);
        });
    }
}
