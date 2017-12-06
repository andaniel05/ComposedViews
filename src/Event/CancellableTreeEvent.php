<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews\Event;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
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
