<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Session;

interface SessionInterface
{
    public function asArray(): array;
    public function clear(): void;
    public function get(string $key, $default = null);
    public function has(string $key): bool;
    public function set(string $key, $value): void;
    public function unset(string $key): void;
}
