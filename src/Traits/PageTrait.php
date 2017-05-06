<?php

namespace PlatformPHP\ComposedViews\Traits;

use PlatformPHP\ComposedViews\AbstractPage;

trait PageTrait
{
    protected $page;

    public function getPage() : ?AbstractPage
    {
        return $this->page;
    }

    public function setPage(?AbstractPage $page)
    {
        $this->page = $page;
    }
}