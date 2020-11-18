<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Html\Table;

use ThreeHundred\Library\Html\AbstractHtmlTag;

class Cell extends AbstractHtmlTag
{
    public const TYPE_DATA = 'td';
    public const TYPE_HEADER = 'th';

    public $colspan;
    public $rowspan;
    public $scope;

    protected $tag = self::TYPE_DATA;

    public function __construct(string $type = self::TYPE_DATA)
    {
        $this->setType($type);
    }

    public function attributes(): array
    {
        $attributes = [
            'colspan=' => $this->colspan,
            'rowspan=' => $this->rowspan,
        ];
        if ($this->tag == self::TYPE_HEADER) {
            $attributes['scope'] = $this->scope;
        }
        return $attributes;
    }

    public function getColspan(): ?int
    {
        return $this->colspan;
    }

    public function getRowspan(): ?int
    {
        return $this->rowspan;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function setColspan(int $colspan): self
    {
        $this->colspan = $colspan;
        return $this;
    }

    public function setRowspan(int $rowspan): self
    {
        $this->rowspan = $rowspan;
        return $this;
    }

    /**
     * This enumerated attribute defines the cells that the header (defined in
     * the <th>) element relates to. It may have the following values:
     *
     * * row: The header relates to all cells of the row it belongs to.
     * * col: The header relates to all cells of the column it belongs to.
     * * rowgroup: The header belongs to a rowgroup and relates to all of its
     * cells. These cells can be placed to the right or the left of the header,
     * depending on the value of the dir attribute in the <table> element.
     * * colgroup: The header belongs to a colgroup and relates to all of its
     * cells.
     * * auto
     *
     * The default value when this attribute is not specified is auto.
     */
    public function setScope(string $scope): self
    {
        $this->scope = $scope;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->tag = $type;
        return $this;
    }
}
