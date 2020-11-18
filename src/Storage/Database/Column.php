<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database;

class Column
{
    private $name;
    private $type;
    private $size;
    private $null = false;
    private $default;
    private $primary = false;
    private $autoIncrement = false;
    private $unsigned = false;

    public function __construct(
        string $name,
        string $type = 'text',
        ?int $size = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
    }

    public function getAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    public function setAutoIncrement(bool $enable = true): self
    {
        $this->autoIncrement = $enable;
        return $this;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($value): self
    {
        $this->default = $value;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNull(): bool
    {
        return $this->null;
    }

    public function setNull(bool $null = true): self
    {
        $this->null = $null;
        return $this;
    }

    public function getPrimary(): bool
    {
        return $this->primary;
    }

    public function setPrimary(bool $primary = true): self
    {
        $this->primary = $primary;
        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUnsigned(): bool
    {
        return $this->unsigned;
    }

    public function setUnsigned(bool $unsigned = true): self
    {
        $this->unsigned = $unsigned;
        return $this;
    }
}
