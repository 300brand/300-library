<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Tests\Storage\Database;

use PHPUnit\Framework\TestCase;
use ThreeHundred\Library\Storage\Database\Column;

class ColumnTest extends TestCase
{
    public function testDefaultAttributes(): void
    {
        $column = new Column('col_a');
        $this->assertEquals($column->getAutoIncrement(), false);
        $this->assertEquals($column->getDefault(), null);
        $this->assertEquals($column->getName(), 'col_a');
        $this->assertEquals($column->getNull(), false);
        $this->assertEquals($column->getPrimary(), false);
        $this->assertEquals($column->getSize(), null);
        $this->assertEquals($column->getType(), 'text');
        $this->assertEquals($column->getUnsigned(), false);
    }

    public function testSetDefaultAttributes(): void
    {
        $column = (new Column('col_a', 'int', 10))
            ->setAutoIncrement()
            ->setDefault(0)
            ->setNull()
            ->setPrimary()
            ->setUnsigned();
        $this->assertEquals($column->getAutoIncrement(), true);
        $this->assertEquals($column->getDefault(), 0);
        $this->assertEquals($column->getName(), 'col_a');
        $this->assertEquals($column->getNull(), true);
        $this->assertEquals($column->getPrimary(), true);
        $this->assertEquals($column->getSize(), 10);
        $this->assertEquals($column->getType(), 'int');
        $this->assertEquals($column->getUnsigned(), true);
    }
}
