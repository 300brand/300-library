<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database\Adapters;

interface DatabaseResultInterface
{
    public function fetch();
    public function fetchAll(): array;
    public function fetchColumn();
}
