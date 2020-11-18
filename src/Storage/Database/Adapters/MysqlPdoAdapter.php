<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database\Adapters;

use PDO;
use ThreeHundred\Library\Storage\Database\Table;

class MysqlPdoAdapter extends AbstractPdoAdapter
{
    public function cast(string $value, string $type): string
    {
        return 'CAST(' . $this->db->quote($value) . ' AS ' . $type . ')';
    }

    public function concat(string ...$values): string
    {
        return 'CONCAT(' . implode(', ', $values) . ')';
    }

    public function getTables(): array
    {
        $sql = 'SHOW TABLES';
        return $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function tableSql(Table $table): array
    {
        $sql = [];

        $columns = [];
        foreach ($table->getColumns() as $column) {
            $type = $column->getType();
            if ($column->getSize() !== null) {
                $type .= '(' . $column->getSize() . ')';
            }
            if (!$column->getNull()) {
                $type .= ' NOT NULL';
            }
            if ($column->getAutoIncrement()) {
                $type .= ' AUTO_INCREMENT';
            }
            if ($column->getPrimary()) {
                $type .= ' PRIMARY KEY';
            }
            $columns[] = sprintf('    `%s` %s', $column->getName(), $type);
        }

        foreach ($table->getIndicies() as $index) {
            $type = 'INDEX';
            if ($index->getUnique()) {
                $type = 'UNIQUE';
            }
            $columns[] = sprintf(
                '    %s `%s_%s_idx` (%s)',
                $type,
                $table->getName(),
                implode('_', $index->getColumns()),
                implode(', ', $index->getColumns())
            );
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
            "CREATE TABLE `%s` (\n%s\n) ENGINE=%s DEFAULT CHARSET=%s",
            $table->getName(),
            implode(",\n", $columns),
            $table->getEngine(),
            $table->getCharset()
        );

        foreach ($table->getQueries() as $query) {
            $sql[] = $query->getQuery();
        }

        return $sql;
    }
}
