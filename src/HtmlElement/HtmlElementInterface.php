<?php

namespace Andaniel05\ComposedViews\HtmlElement;

interface HtmlElementInterface extends HtmlInterface
{
    public function getTag(): string;

    public function setTag(string $tag);

    public function getAttributes(): array;

    public function setAttributes(array $attributes);

    public function getAttribute(string $attribute);

    public function setAttribute(string $attribute, $value);

    public function deleteAttribute(string $attribute);

    public function getContent(): array;

    public function setContent(array $content);

    public function addContent($content);

    public function deleteContent(int $id);

    public function getEndTag(): ?bool;

    public function setEndTag(?bool $endTag);
}
