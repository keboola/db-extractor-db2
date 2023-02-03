<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\TraitTests;

use Keboola\DbExtractor\Exception\UserException;
use Keboola\DbExtractor\Extractor\DB2OdbcConnection;
use PDO;
use Throwable;

trait InsertRowsTrait
{
    use QuoteTrait;
    use QuoteIdentifierTrait;

    protected DB2OdbcConnection $connection;

    public function insertRows(string $tableName, array $columns, array $rows): void
    {
        // Generate columns statement
        $columnsSql = [];
        foreach ($columns as $name) {
            $columnsSql[] = $this->quoteIdentifier($name);
        }

        // Generate values statement
        $valuesSql = [];
        foreach ($rows as $row) {
            $valuesSql[] =
                '(' .
                implode(
                    ', ',
                    array_map(function ($value) {
                        if (is_numeric($value)) {
                            return $value;
                        }
                        if (is_null($value)) {
                            return 'DEFAULT';
                        }
                        return $this->quote((string) $value);
                    }, $row)
                ) .
                ')';
        }

        foreach ($valuesSql as $values) {
            try {
                $this->connection->query(sprintf(
                    'INSERT INTO %s (%s) VALUES %s',
                    $this->quoteIdentifier($tableName),
                    implode(', ', $columnsSql),
                    $values
                ));
            } catch (Throwable $e) {
                throw new UserException($e->getMessage(), (int) $e->getCode(), $e);
            }
        }
    }
}
