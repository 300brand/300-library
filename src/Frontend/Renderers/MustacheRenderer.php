<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend\Renderers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use ThreeHundred\Library\Frontend\Context\ContextInterface;

class MustacheRenderer implements RendererInterface
{
    private $mustache;

    public function __construct(string $templatePath)
    {
        $config = [
            'loader' => new Mustache_Loader_FilesystemLoader($templatePath),
            // 'cache'  => dirname($templatePath) . '/.protected/mustache',
        ];
        $this->mustache = new Mustache_Engine($config);
    }

    public function render(string $path, ContextInterface $context): string
    {
        return $this->mustache->loadTemplate($path)->render($context);
    }
}
