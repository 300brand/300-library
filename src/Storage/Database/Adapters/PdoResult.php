<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database\Adapters;

use PDOStatement;

class PdoResult extends PDOStatement implements DatabaseResultInterface
{
    public function fetch($how = null, $orientation = null, $offset = null)
    {
        if ($how === null) {
            return parent::fetch();
        }
        if ($orientation === null) {
            return parent::fetch($how);
        }
        if ($offset === null) {
            return parent::fetch($how, $orientation);
        }
        return parent::fetch($how, $orientation, $offset);
    }

    public function fetchAll($how = null, $class = null, $args = null): array
    {
        if ($how === null) {
            return parent::fetchAll();
        }
        if ($class === null) {
            return parent::fetchAll($how);
        }
        if ($args === null) {
            return parent::fetchAll($how, $class);
        }
        return parent::fetchAll($how, $class, $args);
    }

    public function fetchColumn($column = 0)
    {
        return parent::fetchColumn($column);
    }
}
