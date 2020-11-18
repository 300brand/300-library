<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend;

use DateTimeInterface;
use InvalidArgumentException;
use NumberFormatter;

class Format
{
    public static function apostrophe($word): string
    {
        $len = strlen($word);
        if ($len == 0) {
            return "";
        }
        if ($word[$len - 1] == 's') {
            return $word . "'";
        }
        return $word . "'s";
    }

    public static function date($date)
    {
        if (empty($date)) {
            return null;
        }
        if ($date instanceof DateTimeInterface) {
            $date = $date->getTimestamp();
        } elseif ((string)(int)$date != $date) {
            $date = strtotime($date);
        }
        if (date('His', $date) == 0) {
            return date(DATE_FORMAT, $date);
        }
        return date(DATETIME_FORMAT, $date);
    }

    public static function decimal($value, int $digits = 0): string
    {
        static $fmt;
        if ($fmt == null) {
            $fmt = new NumberFormatter('en_US', NumberFormatter::DECIMAL);
        }
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, $digits);
        return $fmt->format($value);
    }

    public static function filesize(int $bytes): string
    {
        $s = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        $e = floor(log($bytes) / log(1024));
        if ($bytes == 0) {
            return '0 ' . $s[0];
        }
        return sprintf('%0.2f %s', $bytes / pow(1024, $e), $s[$e]);
    }

    public static function money($value, bool $hideDecimal = false): string
    {
        static $fmt;
        if ($fmt == null) {
            $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        }
        if ($hideDecimal) {
            $fmt->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
        }
        return $fmt->format($value);
    }

    public static function mysqlDate($date): string
    {
        $datetime = self::mysqlDatetime($date);
        return substr($datetime, 0, 10);
    }

    public static function mysqlDatetime($date): string
    {
        if ($date instanceof DateTimeInterface) {
            $date = $date->getTimestamp();
        } elseif (filter_var($date, FILTER_VALIDATE_FLOAT) !== false) {
            $date = intval($date);
        } elseif (filter_var($date, FILTER_VALIDATE_INT) === false) {
            $date = strtotime($date);
        }
        return date('Y-m-d H:i:s', $date);
    }

    public static function percent($value, int $decimal = 2): string
    {
        static $fmt;
        if ($fmt == null) {
            $fmt = new NumberFormatter('en_US', NumberFormatter::PERCENT);
        }
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, $decimal);
        return $fmt->format($value);
    }

    public static function phone(string $num): string
    {
        if (trim($num, '1234567890x') != '') {
            throw new InvalidArgumentException(
                'Invalid characters in phone number: ' . $num
            );
        }
        static $search = [
            '/^(\d{3})(\d{3})(\d{4})x(\d+)$/',
            '/^(\d{3})(\d{4})x(\d+)$/',
            '/^(\d{3})(\d{3})(\d{4})$/',
            '/^(\d{3})(\d{4})$/',
        ];
        static $replace = [
            '$1-$2-$3, ext. $4',
            '$1-$2, ext. $3',
            '$1-$2-$3',
            '$1-$2',
        ];
        return preg_replace($search, $replace, $num);
    }

    public static function serialList(
        array $things,
        string $glue = 'and'
    ): string {
        if (empty($things)) {
            return '';
        }
        $last = array_pop($things);
        if (empty($things)) {
            return (string)$last;
        }
        if (count($things) == 1) {
            return array_pop($things) . " {$glue} " . $last;
        }
        return implode(", ", $things) . ', ' . $glue . ' ' . $last;
    }

    public static function tel(string $num): string
    {
        static $search = [
            '/^(\d{3})(\d{3})(\d{4})x(\d+)$/',
            '/^(\d{3})(\d{4})x(\d+)$/',
            '/^(\d{3})(\d{3})(\d{4})$/',
            '/^(\d{3})(\d{4})$/',
        ];
        static $replace = [
            '$1$2$3,$4',
            '+1$1$2,$3',
            '+1$1$2$3',
            '$1$2',
        ];
        return preg_replace($search, $replace, $num);
    }
}
