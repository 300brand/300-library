<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Html\Table;

class TableFactory
{
    private $class;
    private $data;
    private $title;

    public function __construct(TableDataInterface $data)
    {
        $this->setClass('table table-striped table-hover mb-0');
        $this->data = $data;
    }

    public function html(): string
    {
        if ($this->data->isEmpty()) {
            return '';
        }

        $table = $this->buildTable();

        $title = '';
        if (!empty($this->title)) {
            $title = sprintf(
                '<div class="card-header">%s</div>',
                htmlspecialchars($this->title)
            );
        }

        // Wrap table in a card to gussy up the styling
        $html = '<div class="card card-table">'
            . $title
            . '<div class="card-body p-0">'
            . '<div class="table-responsive">'
            . $table->html()
            . '</div>'
            . '</div>'
            . '</div>';

        return $html;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    private function buildBody(): Body
    {
        $body = new Body();
        foreach ($this->data->getBodyRows() as $row) {
            $body->appendChild($row);
        }
        return $body;
    }

    private function buildFooter(): Foot
    {
        $foot = new Foot();
        foreach ($this->data->getFooterRows() as $row) {
            $foot->appendChild($row);
        }
        return $foot;
    }

    private function buildHeader(): Head
    {
        $head = new Head();
        foreach ($this->data->getHeaderRows() as $row) {
            $head->appendChild($row);
        }
        return $head;
    }

    private function buildTable(): Table
    {
        $table = new Table();
        $table->setClass($this->class);
        $table->appendChild($this->buildHeader());
        $table->appendChild($this->buildBody());
        if ($this->data instanceof TableFooterInterface) {
            $table->appendChild($this->buildFooter());
        }
        return $table;
    }
}
