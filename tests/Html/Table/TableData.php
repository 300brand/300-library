<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Tests\Html\Table;

use ThreeHundred\Library\Html\Table\AbstractTableData;
use ThreeHundred\Library\Html\Table\Cell;
use ThreeHundred\Library\Html\Table\Row;

class TableData extends AbstractTableData
{
    public function getHeaderRows(): array
    {
        $row = (new Row())
            ->appendChild(
                (new Cell())
                    ->setType(Cell::TYPE_HEADER)
                    ->setContent('Column 1')
            )
            ->appendChild(
                (new Cell())
                    ->setType(Cell::TYPE_HEADER)
                    ->setContent('Column 2')
            )
            ->appendChild(
                (new Cell())
                    ->setType(Cell::TYPE_HEADER)
                    ->setContent('Column 3')
            );
        return [$row];
    }
}
