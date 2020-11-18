<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Storage\Database\Adapters;

use PDO;

abstract class AbstractPdoAdapter implements DatabaseAdapterInterface
{
    protected $db;

    public function __construct(
        string $dsn,
        string $user = '',
        string $pass = '',
        array $options = []
    ) {
        $defaults = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT         => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
            PDO::ATTR_STATEMENT_CLASS    => [PdoResult::class],
        ];
        $options = $defaults + $options;
        $this->db = new PDO($dsn, $user, $pass, $options);
    }

    public function query(
        string $sql,
        array $bind = []
    ): DatabaseResultInterface {
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $marker => $value) {
            // Marker for bind-value is 1-indexed. If the array passed in has
            // numeric indexes, add one to match up with the query question
            // marks
            if (is_numeric($marker)) {
                $marker += 1;
            }
            $type = is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($marker, $value, $type);
        }
        if ($stmt->execute() === false) {
            list($code, , $message) = $stmt->errorInfo();
            throw new RuntimeException($code . ': ' . $message);
        }
        return $stmt;
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}
