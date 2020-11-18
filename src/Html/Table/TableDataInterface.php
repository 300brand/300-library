<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Html\Table;

interface TableDataInterface
{
    public function getHeaderRows(): array;
    public function getBodyRows(): array;
    public function isEmpty(): bool;
}
