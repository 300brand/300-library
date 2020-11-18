<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend\Context;

interface ContextInterface
{
    public function set(string $key, $value): self;
    public function toArray(): array;
}
