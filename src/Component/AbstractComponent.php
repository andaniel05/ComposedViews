<?php

namespace Andaniel05\ComposedViews\Component;

use Andaniel05\ComposedViews\{AbstractPage, PageEvents};
use Andaniel05\ComposedViews\HtmlElement\HtmlInterface;
use Andaniel05\ComposedViews\Asset\AssetsTrait;
use Andaniel05\ComposedViews\Event\{BeforeInsertionEvent, AfterInsertionEvent,
    BeforeDeletionEvent, AfterDeletionEvent};
use Andaniel05\ComposedViews\Traits\CloningTrait;

abstract class AbstractComponent implements HtmlInterface
{
    use AssetsTrait;
    use CloningTrait;

    protected $id;
    protected $parent;
    protected $components = [];
    protected $page;

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

    public function renderizeChildren(): ?string
    {
        $result = '';

        foreach ($this->getChildren() as $component) {
            $result .= <<<HTML
<div class="cv-component cv-{$component->getId()}" id="cv-{$component->getId()}">
    {$component->html()}
</div>
HTML;
        }

        return $result;
    }

    public function getChildren(): array
    {
        return $this->components;
    }

    private function findOne(array $components, string $id): ?AbstractComponent
    {
        foreach ($components as $component) {
            if ($id == $component->getId()) {
                return $component;
            } elseif ($component instanceOf AbstractComponent) {
                $component = $this->findOne($component->getChildren(), $id);
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
            return $this->findOne($this->getChildren(), $id);
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
        if ($this->page instanceOf AbstractPage) {

            $beforeInsertionEvent = new BeforeInsertionEvent($this, $component);
            $this->page->getDispatcher()->dispatch(PageEvents::BEFORE_INSERTION, $beforeInsertionEvent);

            if ($beforeInsertionEvent->isCancelled()) {
                return;
            }
        }

        $this->components[$component->getId()] = $component;
        $component->setParent($this);

        if ($this->page instanceOf AbstractPage) {
            $afterInsertionEvent = new AfterInsertionEvent($this, $component);
            $this->page->getDispatcher()->dispatch(PageEvents::AFTER_INSERTION, $afterInsertionEvent);
        }
    }

    public function dropComponent(string $id, bool $notifyChild = true)
    {
        $component = $this->components[$id] ?? null;
        if ($component) {

            $drop = true;

            if ($this->page instanceOf AbstractPage) {

                $beforeDeletionEvent = new BeforeDeletionEvent($this, $component);
                $this->page->getDispatcher()->dispatch(PageEvents::BEFORE_DELETION, $beforeDeletionEvent);

                if ($beforeDeletionEvent->isCancelled()) {
                    $drop = false;
                }
            }

            if ($drop) {

                if ($notifyChild) {
                    $component->setParent(null);
                }

                unset($this->components[$id]);

                if ($this->page instanceOf AbstractPage) {
                    $afterDeletionEvent = new AfterDeletionEvent($this, $component);
                    $this->page->getDispatcher()->dispatch(PageEvents::AFTER_DELETION, $afterDeletionEvent);
                }
            }
        }
    }

    public function existsComponent(string $id): bool
    {
        return isset($this->components[$id]);
    }

    public function getPage(): ?AbstractPage
    {
        return $this->page;
    }

    public function setPage(?AbstractPage $page)
    {
        $this->page = $page;
    }
}