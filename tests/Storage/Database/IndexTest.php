<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Tests\Storage\Database;

use PHPUnit\Framework\TestCase;
use ThreeHundred\Library\Storage\Database\Index;

class IndexTest extends TestCase
{
    public function testSingle(): void
    {
        $index = new Index('a');
        $this->assertFalse($index->getUnique());
        $this->assertEquals(['a'], $index->getColumns());
    }

    public function testMultiple(): void
    {
        $index = new Index('a', 'b');
        $this->assertFalse($index->getUnique());
        $this->assertEquals(['a', 'b'], $index->getColumns());
    }

    public function testUnique(): void
    {
        $index = new Index('a');
        $index->setUnique();
        $this->assertTrue($index->getUnique());
        $this->assertEquals(['a'], $index->getColumns());
    }
}
