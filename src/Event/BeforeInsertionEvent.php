<?php

namespace PlatformPHP\ComposedViews\Event;

use Symfony\Component\EventDispatcher\Event;
use PlatformPHP\ComposedViews\Component\AbstractComponent;

class BeforeInsertionEvent extends Event
{
    protected $parent;
    protected $child;
    protected $cancelled = false;

    public function __construct(AbstractComponent $parent, AbstractComponent $child)
    {
        $this->parent = $parent;
        $this->child = $child;
    }

    public function getParent(): AbstractComponent
    {
        return $this->parent;
    }

    public function getChild(): AbstractComponent
    {
        return $this->child;
    }

    public function cancel(bool $value)
    {
        $this->cancelled = $value;
    }

    public function isCancelled(): bool
    {
        return $this->cancelled;
    }
}