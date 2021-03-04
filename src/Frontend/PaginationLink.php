<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend;

class PaginationLink
{
    public $label;
    public $page;

    protected $is_gap = false;

    protected static $url_vars;

    public function __construct(int $page, string $label = null)
    {
        $this->page = $page;
        if ($label === null) {
            $label = $page;
        }
        $this->label = $label;
    }

    public function isActive(int $page): bool
    {
        return $this->label == $this->page &&
               $this->page == $page;
    }

    public function isDisabled(int $page): bool
    {
        return !$this->is_gap &&
               $this->label != $this->page &&
               $this->page == $page;
    }

    public function setGap(bool $is_gap)
    {
        $this->is_gap = $is_gap;
    }

    public function url(): string
    {
        if ($this->page == 0) {
            return '#';
        }
        if (self::$url_vars === null) {
            parse_str($_SERVER['QUERY_STRING'], self::$url_vars);
        }
        $query = array_merge(self::$url_vars, ['p' => $this->page]);
        return '?' . http_build_query($query);
    }
}
