<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Html\Table;

abstract class AbstractTableData implements TableDataInterface
{
    protected $data = [];

    public function __construct(array $data)
    {
        $this->setData($data);
    }

    public function getBodyRows(): array
    {
        $rows = [];
        foreach ($this->data as $data) {
            $rows[] = $data->getRow();
        }
        return $rows;
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    public function setData(array $data): void
    {
        foreach ($data as $row) {
            if (!$row instanceof TableRowInterface) {
                throw new InvalidTableRowException();
            }
        }
        $this->data = $data;
    }
}
