<?php

namespace PlatformPHP\ComposedViews\Asset2;

abstract class AbstractUrlAsset extends AbstractAsset
{
    protected $url;

    public function __construct(string $id)
    {
        parent::__construct($id);

        $this->addGroup('url');
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url)
    {
        $this->url = $url;
    }
}