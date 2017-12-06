<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews\Event;

class CancellableTreeEvent extends TreeEvent
{
    protected $cancelled = false;

    public function cancel(bool $value)
    {
        $this->cancelled = $value;
    }

    public function isCancelled(): bool
    {
        return $this->cancelled;
    }
}
