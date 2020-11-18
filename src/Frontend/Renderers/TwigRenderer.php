<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend\Renderers;

use ThreeHundred\Library\Frontend\Context\ContextInterface;

class TwigRenderer implements RendererInterface
{
    private $twig;

    public function __construct(string $templatePath)
    {
        $loader = new \Twig\Loader\FilesystemLoader($templatePath);
        $config = [
            'cache' => false,
        ];
        $this->twig = new \Twig\Environment($loader, $config);
    }

    public function render(string $path, ContextInterface $context): string
    {
        $path .= '.twig';
        return $this->twig->render($path, $context->toArray());
    }
}
