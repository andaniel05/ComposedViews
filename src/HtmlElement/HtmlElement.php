<?php

namespace Andaniel05\ComposedViews\HtmlElement;

class HtmlElement implements HtmlElementInterface
{
    protected $tag;
    protected $attributes;
    protected $content;
    protected $endTag;

    public function __construct(string $tag = 'div', array $attributes = [], array $content = [], ?bool $endTag = true)
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

        $content = '';
        foreach ($this->content as $key => $value) {
            if (is_scalar($value)) {
                $content .= $value;
            } elseif ($value instanceOf HtmlElementInterface) {
                $content .= $value->html();
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

    public function setTag(string $tag)
    {
        $this->tag = $tag;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content)
    {
        $this->content = $content;
    }

    public function getEndTag(): ?bool
    {
        return $this->endTag;
    }

    public function setEndTag(?bool $endTag)
    {
        $this->endTag = $endTag;
    }

    public function addAttribute(string $attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    public function deleteAttribute(string $attribute)
    {
        unset($this->attributes[$attribute]);
    }
}