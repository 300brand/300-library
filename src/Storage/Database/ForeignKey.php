<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database;

class ForeignKey
{
    private $localColumn;
    private $foreignTable;
    private $foreignColumn;
    private $onDelete;
    private $onUpdate;

    public function __construct(
        string $localColumn,
        string $foreignTable,
        string $foreignColumn,
        string $onDelete = 'restrict',
        string $onUpdate = 'cascade'
    ) {
        $this->localColumn = $localColumn;
        $this->foreignTable = $foreignTable;
        $this->foreignColumn = $foreignColumn;
        $this->onDelete = $onDelete;
        $this->onUpdate = $onUpdate;
    }

    public function getForeignColumn(): string
    {
        return $this->foreignColumn;
    }

    public function getForeignTable(): string
    {
        return $this->foreignTable;
    }

    public function getLocalColumn(): string
    {
        return $this->localColumn;
    }

    public function getOnDelete(): string
    {
        return $this->onDelete;
    }

    public function getOnUpdate(): string
    {
        return $this->onUpdate;
    }
}
