<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database\Adapters;

use PDO;
use ThreeHundred\Library\Storage\Database\Table;

class SqlitePdoAdapter extends AbstractPdoAdapter
{
    public function __construct(string $dsn, array $options = [])
    {
        parent::__construct($dsn, '', '', $options);
        $this->db->exec('PRAGMA foreign_keys = ON');
    }
    public function cast(string $value, string $type): string
    {
        return 'CAST(' . $this->db->quote($value) . ' AS ' . $type . ')';
    }

    public function concat(string ...$values): string
    {
        return implode(' || ', $values);
    }

    public function getTables(): array
    {
        $sql = 'SELECT name FROM sqlite_master WHERE type="table"';
        return $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function tableSql(Table $table): array
    {
        $sql = [];

        $columns = [];
        foreach ($table->getColumns() as $column) {
            $type = $column->getType();
            if ($column->getAutoIncrement()) {
                $type = 'integer';
            }
            if ($column->getSize() !== null) {
                $type .= '(' . $column->getSize() . ')';
            }
            if (!$column->getNull()) {
                $type .= ' NOT NULL';
            }
            if ($column->getPrimary()) {
                $type .= ' PRIMARY KEY';
                if ($column->getAutoIncrement()) {
                    $type .= ' AUTOINCREMENT';
                }
            }
            $columns[] = sprintf('    `%s` %s', $column->getName(), $type);
        }

        foreach ($table->getForeignKeys() as $key) {
            $columns[] = sprintf(
                '    FOREIGN KEY (%s) REFERENCES %s (%s) '
                . 'ON DELETE %s ON UPDATE %s',
                $key->getLocalColumn(),
                $key->getForeignTable(),
                $key->getForeignColumn(),
                $key->getOnDelete(),
                $key->getOnUpdate()
            );
        }

        $sql[] = sprintf(
            "CREATE TABLE `%s` (\n%s\n)",
            $table->getName(),
            implode(",\n", $columns)
        );

        foreach ($table->getIndicies() as $index) {
            $unique = $index->getUnique() ? 'UNIQUE' : '';
            $sql[] = sprintf(
                'CREATE %s INDEX %s_%s_idx ON %s (%s)',
                $unique,
                $table->getName(),
                implode('_', $index->getColumns()),
                $table->getName(),
                implode(', ', $index->getColumns())
            );
        }

        foreach ($table->getQueries() as $query) {
            $sql[] = $query->getQuery();
        }

        return $sql;
    }
}
