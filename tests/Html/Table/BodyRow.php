<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Tests\Html\Table;

use ThreeHundred\Library\Html\Table\Cell;
use ThreeHundred\Library\Html\Table\Row;
use ThreeHundred\Library\Html\Table\TableRowInterface;

class BodyRow implements TableRowInterface
{
    public $col1;
    public $col2;
    public $col3;

    public function getRow(): Row
    {
        $row = (new Row())
            ->appendChild((new Cell())->setContent($this->col1))
            ->appendChild((new Cell())->setContent($this->col2))
            ->appendChild((new Cell())->setContent($this->col3));
        return $row;
    }
}
