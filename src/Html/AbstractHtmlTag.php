<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Html;

use InvalidArgumentException;
use UnexpectedValueException;

abstract class AbstractHtmlTag implements HtmlTagInterface
{
    private const HTML_FLAGS = ENT_QUOTES | ENT_HTML5;

    protected $children = [];
    protected $class;
    protected $content = '';
    protected $escapeContent = false;
    protected $hasEndTag = true;
    protected $id;
    protected $style;
    protected $tag = 'undefined-tag';

    public function appendChild(
        HtmlTagInterface $child,
        ?string $key = null
    ): HtmlTagInterface {
        if ($key === null) {
            $this->children[] = $child;
        } else {
            $this->children[$key] = $child;
        }
        return $this;
    }

    public function getChild($index): ?HtmlTagInterface
    {
        if (!array_key_exists($index, $this->children)) {
            return null;
        }
        return $this->children[$index];
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function html(): string
    {
        $html = '<' . $this->tag;
        $attributes = array_filter(array_merge(
            $this->defaultAttributes(),
            $this->attributes()
        ));
        foreach ($attributes as $attribute => $value) {
            $html .= ' ' . $attribute;
            if (substr($attribute, -1) == '=') {
                $attr = '"'
                      . htmlspecialchars((string)$value, self::HTML_FLAGS)
                      . '"';
                $html .= $attr;
            }
        }

        $html .= '>';

        if (!$this->hasEndTag) {
            return $html;
        }

        $innerHtml = $this->innerHtml();
        if ($innerHtml != '' && $this->escapeContent) {
            $innerHtml = htmlspecialchars($innerHtml, self::HTML_FLAGS);
        }
        $html .= $innerHtml;
        $html .= '</' . $this->tag . '>';
        return $html;
    }

    public function removeChild(HtmlTagInterface $child): HtmlTagInterface
    {
        $hash = spl_object_hash($child);
        foreach ($this->children as $key => $value) {
            if (spl_object_hash($value) == $hash) {
                unset($this->children[$key]);
                return $this;
            }
        }
        throw new InvalidArgumentException('Unable to find child');
    }

    public function setClass(string $class): HtmlTagInterface
    {
        $this->class = $class;
        return $this;
    }

    public function setContent(string $content): HtmlTagInterface
    {
        $this->content = $content;
        return $this;
    }

    public function setEscapeContent(bool $escape = true): HtmlTagInterface
    {
        $this->escapeContent = $escape;
        return $this;
    }

    public function setId(string $id): HtmlTagInterface
    {
        $this->id = $id;
        return $this;
    }

    public function setStyle(string $style): HtmlTagInterface
    {
        $this->style = $style;
        return $this;
    }

    private function defaultAttributes(): array
    {
        return [
            'class=' => $this->class,
            'id='    => $this->id,
            'style=' => $this->style,
        ];
    }
    private function innerHtml(): string
    {
        if (!empty($this->children) && !empty($this->content)) {
            throw new UnexpectedValueException(
                'Cannot define both children and content.'
            );
        }
        return array_reduce(
            $this->children,
            function (string $carry, self $item): string {
                return $carry . $item->html();
            },
            $this->content
        );
    }
}
