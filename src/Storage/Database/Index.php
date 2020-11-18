<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database;

class Index
{
    private $columns = [];
    private $unique = false;

    public function __construct(...$columns)
    {
        $this->columns = $columns;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getUnique(): bool
    {
        return $this->unique;
    }

    public function setUnique(bool $unique = true): self
    {
        $this->unique = $unique;
        return $this;
    }
}
