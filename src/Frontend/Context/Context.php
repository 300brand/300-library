<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend\Context;

use ArrayObject;

class Context extends ArrayObject implements ContextInterface
{
    public function set(string $key, $value): ContextInterface
    {
        $this->offsetSet($key, $value);
        return $this;
    }

    public function toArray(): array
    {
        return $this->getArrayCopy();
    }
}
