<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Tests\Html\Table;

use PHPUnit\Framework\TestCase;
use ThreeHundred\Library\Html\Table\TableFactory;

class TableFactoryTest extends TestCase
{
    public function testSimple(): void
    {
        $data = [new BodyRow(), new BodyRow()];
        $data[0]->col1 = 'abc';
        $data[0]->col2 = 'def';
        $data[0]->col3 = 'ghi';
        $data[1]->col1 = 'jkl';
        $data[1]->col2 = 'mno';
        $data[1]->col3 = 'pqr';

        $table = new TableFactory(new TableData($data));

        $expected = trim(file_get_contents(__DIR__ . '/testSimple.out'));
        $this->assertEquals($expected, $table->html());
    }
}
