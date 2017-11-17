<?php

namespace Andaniel05\ComposedViews\HtmlElement;

class HtmlElement implements HtmlElementInterface
{
    protected $tag;
    protected $attributes;
    protected $content;
    protected $endTag;

    public function __construct(string $tag = 'div', array $attributes = [], $content = [], ?bool $endTag = true)
    {
        $this->tag = $tag;
        $this->attributes = $attributes;
        $this->content = $content;
        $this->endTag = $endTag;
    }

    public function html(): ?string
    {
        $result = "<{$this->tag}";

        foreach ($this->attributes as $attr => $value) {
            $result .= " {$attr}=\"{$value}\"";
        }

        $inLineEndTag = $this->endTag === null ? ' /' : null;

        $content = $this->content;

        if (is_array($content)) {
            $content = '';
            foreach ($this->content as $key => $value) {
                if (is_scalar($value)) {
                    $content .= $value;
                } elseif ($value instanceOf HtmlElementInterface) {
                    $content .= $value->html();
                }
            }
        }

        $result .= "{$inLineEndTag}>{$content}";

        if ($this->endTag) {
            $result .= "</{$this->tag}>";
        }

        return $result;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function getEndTag(): ?bool
    {
        return $this->endTag;
    }

    public function setEndTag(?bool $endTag): void
    {
        $this->endTag = $endTag;
    }

    public function setAttribute(string $attribute, $value): void
    {
        $this->attributes[$attribute] = $value;
    }

    public function deleteAttribute(string $attribute): void
    {
        unset($this->attributes[$attribute]);
    }

    public function addContent($content): void
    {
        if (is_array($this->content)) {
            $this->content[] = $content;
        } elseif (is_scalar($this->content)) {
            $this->content = [$this->content, $content];
        }
    }

    public function deleteContent(int $id): void
    {
        unset($this->content[$id]);
    }

    public function getAttribute(string $attribute)
    {
        return $this->attributes[$attribute] ?? null;
    }
}
