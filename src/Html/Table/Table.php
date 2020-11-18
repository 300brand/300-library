<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Html\Table;

use ThreeHundred\Library\Html\AbstractHtmlTag;

class Table extends AbstractHtmlTag
{
    protected $tag = 'table';

    public function attributes(): array
    {
        return [];
    }
}
