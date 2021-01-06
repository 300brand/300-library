<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database;

class Table
{
    private $name;
    private $charset = 'utf8mb4';
    private $engine = 'InnoDB';
    private $columns = [];
    private $foreignKeys = [];
    private $indicies = [];
    private $queries = [];

    public function __construct(string $name, callable $definition)
    {
        $this->name = $name;
        call_user_func($definition, $this);
    }

    public function char(string $name, int $length): Column
    {
        $column = new Column($name, 'char', $length);
        $this->columns[] = $column;
        return $column;
    }

    public function datetime(string $name): Column
    {
        $column = new Column($name, 'datetime');
        $this->columns[] = $column;
        return $column;
    }

    public function foreignKey(
        string $localColumn,
        string $foreignTable,
        string $foreignColumn,
        string $onDelete = 'restrict',
        string $onUpdate = 'cascade'
    ): ForeignKey {
        $fk = new ForeignKey(
            $localColumn,
            $foreignTable,
            $foreignColumn,
            $onDelete,
            $onUpdate
        );
        $this->foreignKeys[] = $fk;
        return $fk;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getEngine(): string
    {
        return $this->engine;
    }

    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }

    public function getIndicies(): array
    {
        return $this->indicies;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQueries(): array
    {
        return $this->queries;
    }

    public function index(string ...$columns): Index
    {
        $index = new Index(...$columns);
        $this->indicies[] = $index;
        return $index;
    }

    public function int(string $name): Column
    {
        $column = new Column($name, 'int');
        $this->columns[] = $column;
        return $column;
    }

    public function query(string $sql): Query
    {
        $query = new Query($sql);
        $this->queries[] = $query;
        return $query;
    }

    public function text(string $name): Column
    {
        $column = new Column($name, 'text');
        $this->columns[] = $column;
        return $column;
    }

    public function varchar(string $name, int $length = 255): Column
    {
        $column = new Column($name, 'varchar', $length);
        $this->columns[] = $column;
        return $column;
    }
}
