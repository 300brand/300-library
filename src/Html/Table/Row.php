<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Html\Table;

use ThreeHundred\Library\Html\AbstractHtmlTag;

class Row extends AbstractHtmlTag
{
    protected $escapeContent = false;
    protected $tag = 'tr';

    public function attributes(): array
    {
        return [];
    }
}
