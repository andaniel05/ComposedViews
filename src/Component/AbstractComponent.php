<?php

namespace Andaniel05\ComposedViews\Component;

use Andaniel05\ComposedViews\{PageInterface, PageEvents};
use Andaniel05\ComposedViews\Asset\AssetsTrait;
use Andaniel05\ComposedViews\Event\{BeforeInsertionEvent, AfterInsertionEvent,
    BeforeDeletionEvent, AfterDeletionEvent};
use Andaniel05\ComposedViews\Traits\CloningTrait;

abstract class AbstractComponent implements ComponentInterface
{
    use AssetsTrait;
    use CloningTrait;

    use ComponentTreeTrait {
        getComponent as getChild;
    }

    protected $id;
    protected $parent;
    protected $page;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getParent(): ?ComponentInterface
    {
        return $this->parent;
    }

    public function setParent(?ComponentInterface $parent)
    {
        $this->parent = $parent;
    }

    public function detach(): void
    {
        if ($this->parent) {
            $this->parent->dropChild($this->id);
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

    public function addChild(ComponentInterface $component)
    {
        if ($this->page instanceOf PageInterface) {

            $beforeInsertionEvent = new BeforeInsertionEvent($this, $component);
            $this->page->getDispatcher()->dispatch(PageEvents::BEFORE_INSERTION, $beforeInsertionEvent);

            if ($beforeInsertionEvent->isCancelled()) {
                return;
            }
        }

        $this->components[$component->getId()] = $component;
        $component->setParent($this);

        if ($this->page instanceOf PageInterface) {
            $afterInsertionEvent = new AfterInsertionEvent($this, $component);
            $this->page->getDispatcher()->dispatch(PageEvents::AFTER_INSERTION, $afterInsertionEvent);
        }
    }

    public function dropChild(string $id, bool $notifyChild = true)
    {
        $component = $this->components[$id] ?? null;
        if ($component) {

            $drop = true;

            if ($this->page instanceOf PageInterface) {

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

                if ($this->page instanceOf PageInterface) {
                    $afterDeletionEvent = new AfterDeletionEvent($this, $component);
                    $this->page->getDispatcher()->dispatch(PageEvents::AFTER_DELETION, $afterDeletionEvent);
                }
            }
        }
    }

    public function hasRootChild(string $id): bool
    {
        return isset($this->components[$id]);
    }

    public function getPage(): ?PageInterface
    {
        return $this->page;
    }

    public function setPage(?PageInterface $page)
    {
        $this->page = $page;
    }

    abstract public function html(): ?string;
}