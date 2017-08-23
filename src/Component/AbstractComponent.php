<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\{AbstractPage, HtmlInterface};
use PlatformPHP\ComposedViews\Asset\AssetsTrait;
use PlatformPHP\ComposedViews\Traits\{PageTrait, CloningTrait};

abstract class AbstractComponent implements HtmlInterface
{
    use AssetsTrait;
    use PageTrait;
    use CloningTrait;

    protected $id;
    protected $parent;
    protected $components = [];

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getParent(): ?AbstractComponent
    {
        return $this->parent;
    }

    public function setParent(?AbstractComponent $parent)
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

    public function getAllComponents(): array
    {
        return $this->components;
    }

    private function findOne(array $components, string $id): ?AbstractComponent
    {
        foreach ($components as $component) {
            if ($id == $component->getId()) {
                return $component;
            } elseif ($component instanceOf AbstractComponent) {
                $component = $this->findOne($component->getAllComponents(), $id);
                if ($component) {
                    return $component;
                }
            }
        }

        return null;
    }

    public function getComponent(string $id): ?AbstractComponent
    {
        $idList = preg_split('/\s+/', $id);

        if (1 == count($idList)) {
            return $this->findOne($this->getAllComponents(), $id);
        }

        $hash = array_fill_keys($idList, null);

        $container = $this;
        for ($i = 0; $i < count($idList); $i++) {

            $componentId = $idList[$i];
            $component = $container->getComponent($componentId);

            if ($component) {
                $hash[$componentId] = $component;
                $container = $component;
            } else {
                break;
            }
        }

        return array_pop($hash);
    }

    public function addComponent(AbstractComponent $component)
    {
        $this->components[$component->getId()] = $component;

        $component->setParent($this);
    }

    public function dropComponent(string $id, bool $notifyChild = true)
    {
        $component = $this->components[$id] ?? null;
        if ($component) {

            if ($notifyChild) {
                $component->setParent(null);
            }

            unset($this->components[$id]);
        }
    }

    public function existsComponent(string $id): bool
    {
        return isset($this->components[$id]);
    }
}