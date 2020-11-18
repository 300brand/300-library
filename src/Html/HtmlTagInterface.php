<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Html;

interface HtmlTagInterface
{
    public function appendChild(self $child, ?string $key = null): self;
    public function attributes(): array;
    public function getChild($index): ?self;
    public function getChildren(): array;
    public function getClass(): string;
    public function getContent(): string;
    public function getId(): string;
    public function getStyle(): string;
    public function html(): string;
    public function removeChild(self $child): self;
    public function setClass(string $class): self;
    public function setContent(string $content): self;
    public function setId(string $id): self;
    public function setStyle(string $style): self;
}
