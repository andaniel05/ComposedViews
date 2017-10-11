<?php

namespace Andaniel05\ComposedViews\HtmlElement;

interface HtmlElementInterface extends HtmlInterface
{
    public function getTag(): string;

    public function setTag(string $tag);

    public function getAttributes(): array;

    public function setAttributes(array $attributes);

    public function getContent(): ?string;

    public function setContent(?string $content);

    public function getEndTag(): ?bool;

    public function setEndTag(?bool $endTag);

    public function addAttribute(string $attribute, $value);

    public function deleteAttribute(string $attribute);
}