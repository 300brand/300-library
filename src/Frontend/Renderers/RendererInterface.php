<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend\Renderers;

use ThreeHundred\Library\Frontend\Context\ContextInterface;

interface RendererInterface
{
    public function __construct(string $templatePath);
    public function render(string $path, ContextInterface $context): string;
}
