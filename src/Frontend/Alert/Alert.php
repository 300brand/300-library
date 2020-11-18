<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend\Alert;

class Alert
{
    public const SUCCESS = 'alert-success';
    public const INFO    = 'alert-info';
    public const WARNING = 'alert-warning';
    public const ERROR   = 'alert-danger';

    private $type;
    private $message;

    public function __construct(string $message, string $type = self::INFO)
    {
        $this->type = $type;
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
