<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database;

class Query
{
    private $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function getQuery(): string
    {
        return $this->query;
    }
}
