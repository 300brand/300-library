<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Tests\Storage\Database;

use PHPUnit\Framework\TestCase;
use ThreeHundred\Library\Storage\Database\Query;

class QueryTest extends TestCase
{
    public function testSetDefaultAttributes(): void
    {
        $str = 'test query';
        $query = new Query($str);
        $this->assertEquals($str, $query->getQuery());
    }
}
