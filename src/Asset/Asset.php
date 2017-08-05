<?php

namespace PlatformPHP\ComposedViews\Asset;

class Asset implements AssetInterface
{
    protected $id;
    protected $group;
    protected $url;
    protected $deps;
    protected $content;
    protected $used = false;

    public function __construct(string $id, string $group, ?string $url, array $deps = [], ?string $content = null)
    {
        $this->id      = $id;
        $this->group   = $group;
        $this->url     = $url;
        $this->deps    = $deps;
        $this->content = $content;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content)
    {
        $this->content = $content;

        return $this;
    }

    public function getDependencies(): array
    {
        return $this->deps;
    }

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function setUsed(bool $used)
    {
        $this->used = $used;
    }

    public function setGroup(string $group): void
    {
        $this->group = $group;
    }
}