<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database\Adapters;

use ThreeHundred\Library\Storage\Database\Table;

interface DatabaseAdapterInterface
{
    public function cast(string $value, string $type): string;
    public function concat(string ...$values): string;
    public function getTables(): array;
    public function lastInsertId();
    public function query(
        string $sql,
        array $parameters = []
    ): DatabaseResultInterface;
    public function tableSql(Table $table): array;
}
