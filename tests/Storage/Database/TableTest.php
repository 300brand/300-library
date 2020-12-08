<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Tests\Storage\Database;

use PHPUnit\Framework\TestCase;
use ThreeHundred\Library\Storage\Database\Column;
use ThreeHundred\Library\Storage\Database\ForeignKey;
use ThreeHundred\Library\Storage\Database\Index;
use ThreeHundred\Library\Storage\Database\Query;
use ThreeHundred\Library\Storage\Database\Table;

class TableTest extends TestCase
{
    public function testEmpty(): void
    {
        $table = new Table('tbl', function (Table $table) {
            // NOOP
        });

        $this->assertEquals('utf8mb4', $table->getCharset());
        $this->assertEquals('InnoDB', $table->getEngine());
        $this->assertEquals('tbl', $table->getName());
        $this->assertEmpty($table->getColumns());
        $this->assertEmpty($table->getForeignKeys());
        $this->assertEmpty($table->getIndicies());
        $this->assertEmpty($table->getQueries());
    }

    public function testConvenienceMethods(): void
    {
        $table = new Table('tbl', function (Table $table) {
            $table->int('a');
            $table->int('b');
            $table->text('txt');
            $table->varchar('vchar');
            $table->varchar('vchar2', 16);
            $table->char('c', 32);
            $table->datetime('d');
            $table->foreignKey('a', 'tbl2', 'b', 'set null', 'cascade');
            $table->index('a', 'b');
            $table->query('INSERT INTO tbl (a) VALUES ("abc")');
        });

        $columns = $table->getColumns();
        foreach ($columns as $column) {
            $this->assertInstanceOf(Column::class, $column);
        }

        $this->assertEquals('a', $columns[0]->getName());
        $this->assertEquals('int', $columns[0]->getType());

        $this->assertEquals('txt', $columns[2]->getName());
        $this->assertEquals('text', $columns[2]->getType());

        $this->assertEquals('vchar', $columns[3]->getName());
        $this->assertEquals('varchar', $columns[3]->getType());
        $this->assertEquals(255, $columns[3]->getSize());

        $this->assertEquals('vchar2', $columns[4]->getName());
        $this->assertEquals('varchar', $columns[4]->getType());
        $this->assertEquals(16, $columns[4]->getSize());

        $this->assertEquals('c', $columns[5]->getName());
        $this->assertEquals('char', $columns[5]->getType());
        $this->assertEquals(32, $columns[5]->getSize());

        $this->assertEquals('d', $columns[6]->getName());
        $this->assertEquals('datetime', $columns[6]->getType());

        $fks = $table->getForeignKeys();
        $this->assertCount(1, $fks);

        $fk = $fks[0];
        $this->assertInstanceOf(ForeignKey::class, $fk);
        $this->assertEquals('a', $fk->getLocalColumn());
        $this->assertEquals('tbl2', $fk->getForeignTable());
        $this->assertEquals('b', $fk->getForeignColumn());
        $this->assertEquals('set null', $fk->getOnDelete());
        $this->assertEquals('cascade', $fk->getOnUpdate());

        $indicies = $table->getIndicies();
        $this->assertCount(1, $indicies);

        $index = $indicies[0];
        $this->assertInstanceOf(Index::class, $index);
        $this->assertEquals(['a', 'b'], $index->getColumns());
        $this->assertFalse($index->getUnique());

        $queries = $table->getQueries();
        $this->assertCount(1, $queries);

        $query = $queries[0];
        $this->assertInstanceOf(Query::class, $query);
        $this->assertEquals(
            'INSERT INTO tbl (a) VALUES ("abc")',
            $query->getQuery()
        );
    }
}
