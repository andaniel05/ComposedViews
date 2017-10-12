<?php

namespace Andaniel05\ComposedViews\Asset;

trait UpdateHtmlElementTagAssetTrait
{
    public function updateHtmlElement()
    {
        if ($this->minimized) {
            $this->element->setContent([$this->getMinimizedContent()]);
        } else {
            $this->element->setContent(["\n", $this->getContent(), "\n"]);
        }
    }
}