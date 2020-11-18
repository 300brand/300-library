<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Tests\Storage\Database;

use PHPUnit\Framework\TestCase;
use ThreeHundred\Library\Storage\Database\ForeignKey;

class ForeignKeyTest extends TestCase
{
    public function testSimple(): void
    {
        $fk = new ForeignKey('local_col', 'foreign_tbl', 'foreign_col');
        $this->assertEquals('local_col', $fk->getLocalColumn());
        $this->assertEquals('foreign_tbl', $fk->getForeignTable());
        $this->assertEquals('foreign_col', $fk->getForeignColumn());
        $this->assertEquals('restrict', $fk->getOnDelete());
        $this->assertEquals('cascade', $fk->getOnUpdate());
    }

    public function testSimpleWithOnClauses(): void
    {
        $fk = new ForeignKey('a', 'b', 'c', 'set null', 'set null');
        $this->assertEquals('a', $fk->getLocalColumn());
        $this->assertEquals('b', $fk->getForeignTable());
        $this->assertEquals('c', $fk->getForeignColumn());
        $this->assertEquals('set null', $fk->getOnDelete());
        $this->assertEquals('set null', $fk->getOnUpdate());
    }
}
