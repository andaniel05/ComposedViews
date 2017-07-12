<?php

namespace PlatformPHP\ComposedViews;

interface HtmlInterface
{
    public function html(): ?string;

    public function print(): void;
}