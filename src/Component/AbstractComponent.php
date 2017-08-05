<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\{AbstractPage, HtmlInterface};
use PlatformPHP\ComposedViews\Asset\AssetsTrait;
use PlatformPHP\ComposedViews\Traits\{PrintTrait, PageTrait, CloningTrait};

abstract class AbstractComponent implements HtmlInterface, ComponentContainerInterface
{
    use AssetsTrait;
    use PrintTrait;
    use PageTrait;
    use CloningTrait;
    use ComponentContainerTrait;

    protected $id;
    protected $parent;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getParent(): ?ComponentContainerInterface
    {
        return $this->parent;
    }

    public function setParent(?ComponentContainerInterface $parent)
    {
        $this->parent = $parent;
    }

    public function detach(): void
    {
        if ($this->parent) {
            $this->parent->dropComponent($this->id);
        }
    }

    public function childrenHtml(): ?string
    {
        $result = '';

        foreach ($this->getAllComponents() as $component) {
            $result .= <<<HTML
<div class="cv-component cv-{$component->getId()}" id="cv-{$component->getId()}">
    {$component->html()}
</div>
HTML;
        }

        return $result;
    }
}