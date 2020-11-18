<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend;

class Pagination
{
    private const DEFAULT_PER_PAGE = 100;

    protected $nextLabel     = "Next";
    protected $previousLabel = "Previous";
    protected $gapLabel      = "&hellip;";
    protected $navLabel      = "Pagination";
    protected $page          = null;
    protected $perPage       = null;
    protected $totalRows     = null;
    protected $shoulder      = 2;

    public function getOffset(): int
    {
        return ($this->getPage() - 1) * $this->getPerPage();
    }

    public function getPerPage(): int
    {
        if ($this->perPage === null) {
            $this->perPage = max(0, $this->getInput('pp'));
        }
        if ($this->perPage == 0) {
            $this->perPage = self::DEFAULT_PER_PAGE;
        }
        return $this->perPage;
    }

    public function html(): string
    {
        $links = $this->getLinks();
        if (empty($links)) {
            return "";
        }
        return $this->nav($links);
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function setTotal(int $totalRows): self
    {
        $this->totalRows = $totalRows;
        return $this;
    }

    private function getLinks(): array
    {
        $lastPage = $this->getPageCount();
        $page = $this->getPage();
        $pages = $this->getWindowPages();
        if (empty($pages)) {
            return [];
        }
        $links = [];
        foreach ($pages as $pageNum) {
            $links[] = new PaginationLink($pageNum);
        }
        // Add window gaps
        $gap = new PaginationLink(0, $this->gapLabel);
        $gap->setGap(true);
        if ($pages[0] > 2) {
            array_unshift($links, $gap);
        }
        if (end($pages) + 1 < $lastPage) {
            array_push($links, $gap);
        }
        // Add page 1
        if ($pages[0] > 1) {
            array_unshift($links, new PaginationLink(1));
        }
        // Add last page
        if (end($pages) < $lastPage) {
            array_push($links, new PaginationLink($lastPage));
        }
        // Add previous and next
        array_unshift(
            $links,
            new PaginationLink(max(1, $page - 1), $this->previousLabel)
        );
        array_push(
            $links,
            new PaginationLink(min($lastPage, $page + 1), $this->nextLabel)
        );
        return $links;
    }

    private function getNavLabel(): string
    {
        return $this->navLabel;
    }

    private function getPage(): int
    {
        if ($this->page === null) {
            $this->page = max(1, $this->getInput('p'));
        }
        return $this->page;
    }

    private function getPageCount(): int
    {
        return intval(ceil($this->getTotal() / $this->getPerPage()));
    }

    private function getTotal()
    {
        return $this->totalRows;
    }

    /**
     * Window Pages are the pages between 1 and the last page number, centered
     * on the current page when the current page is between 1 and the last page.
     *
     * This is how you get pagination that looks like:
     * 1 ... 3 4 5 6 7 ... 10
     *
     * The shoulder defines how many pages on each side of the current page to
     * display.
     *
     * @return array Page numbers for window pages
     */
    private function getWindowPages(): array
    {
        $page = $this->getPage();
        $lastPage = $this->getPageCount();
        if ($lastPage <= 1) {
            return [];
        }
        // The easy one: all pages fit inside the window
        if ($this->shoulder * 2 + 3 >= $lastPage) {
            return range(1, $lastPage);
        }
        // The tough one: create a window centered around the current page
        $min = 2;
        $max = $lastPage - 1;
        $pages = range(
            min($max - $this->shoulder * 2, max($min, $page - $this->shoulder)),
            max($min + $this->shoulder * 2, min($max, $page + $this->shoulder))
        );
        return $pages;
    }

    /**
     * Retrieves value from HTTP GET and validates it as an integer
     *
     * @param string $name Key name of GET query string paramenter
     *
     * @return int Value of query string parameter
     *
     * @codeCoverageIgnore
     */
    protected function getInput(string $name): int
    {
        $value = filter_input(INPUT_GET, $name, FILTER_VALIDATE_INT);
        if ($value === false || $value === null) {
            $value = 0;
        }
        return $value;
    }

    private function nav(array $links): string
    {
        $html  = '<nav aria-label="' . $this->getNavLabel() . '">';
        $html .= $this->list($links);
        $html .= '</nav>';
        return $html;
    }

    private function link(PaginationLink $link): string
    {
        $html  = '<li class="page-item';
        if ($link->isDisabled($this->getPage())) {
            $html .= ' disabled';
        }
        if ($link->isActive($this->getPage())) {
            $html .= ' active';
        }
        $html .= '"><a href="' . $link->url() . '" class="page-link">';
        $html .= htmlize($link->label);
        if ($link->isActive($this->getPage())) {
            $html .= ' <span class="sr-only">(current)</span>';
        }
        $html .= '</a></li>';
        return $html;
    }

    private function list(array $links): string
    {
        $html  = '<ul class="pagination justify-content-center">';
        foreach ($links as $link) {
            $html .= $this->link($link);
        }
        $html .= '</ul>';
        return $html;
    }
}
