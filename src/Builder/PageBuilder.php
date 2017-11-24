<?php

namespace Andaniel05\ComposedViews\Builder;

class PageBuilder extends Builder
{
    public function __construct()
    {
        parent::__construct();

        $this->onTag('page', function ($event) {
            $element = $event->getXMLElement();
            $class = (string) $element['class'];

            $page = new $class;
            $event->setEntity($page);
        });
    }
}
