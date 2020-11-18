<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend\Alert;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class AlertStash implements IteratorAggregate
{
    private $alerts = [];

    public function add(Alert $alert): void
    {
        $this->alerts[] = $alert;
    }

    public function getIterator(): Traversable
    {
        $iterator = new ArrayIterator($this->alerts);
        $this->alerts = [];
        return $iterator;
    }
}
