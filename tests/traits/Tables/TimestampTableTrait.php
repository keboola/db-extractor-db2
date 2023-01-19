<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\TraitTests\Tables;

use Keboola\DbExtractor\TraitTests\AddConstraintTrait;
use Keboola\DbExtractor\TraitTests\CreateTableTrait;
use Keboola\DbExtractor\TraitTests\InsertRowsTrait;

trait TimestampTableTrait
{
    use CreateTableTrait;
    use InsertRowsTrait;
    use AddConstraintTrait;

    public function createTimestampTable(string $name = 'timestamp_test'): void
    {
        $this->createTable($name, $this->getTimestampColumns());
    }

    public function generateTimestampRows(string $tableName = 'timestamp_test'): void
    {
        $data = $this->getTimestampRows();
        $this->insertRows($tableName, $data['columns'], $data['data']);
    }

    private function getTimestampRows(): array
    {
        return [
            'columns' => [
                'id', 'timestamp',
            ],
            // timestamp is generated value, so it is not present in insert statements
            'data' => [
                [1, '2023-01-19 08:22:52.755683'],
                [2, '2023-01-18 08:22:52.755683'],
                [3, '2023-01-19 01:22:52.755683'],
            ],
        ];
    }

    private function getTimestampColumns(): array
    {
        return [
            'id' => 'INT NOT NULL',
            // timestamp is very special type, it is row version
            'timestamp' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ];
    }
}
