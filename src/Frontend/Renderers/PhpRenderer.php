<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend\Renderers;

use ThreeHundred\Library\Frontend\Context\ContextInterface;

class PhpRenderer implements RendererInterface
{
    private const EXT = '.html.php';

    private $templatePath;

    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function render(string $path, ContextInterface $context): string
    {
        $fullPath = $this->templatePath . '/' . $path . self::EXT;
        ob_start();
        extract($context);
        include $fullPath;
        return ob_get_clean();
    }
}
