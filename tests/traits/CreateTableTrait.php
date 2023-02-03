<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\TraitTests;

use Keboola\DbExtractor\Extractor\DB2OdbcConnection;
use PDO;

trait CreateTableTrait
{
    use QuoteIdentifierTrait;

    protected DB2OdbcConnection $connection;

    public function createTable(string $tableName, array $columns): void
    {
        // Generate columns statement
        $columnsSql = [];
        foreach ($columns as $name => $sqlDef) {
            $columnsSql[] = $this->quoteIdentifier($name) . ' ' . $sqlDef;
        }

        // Create table
        $this->connection->query(sprintf(
            'CREATE TABLE %s (%s)',
            $this->quoteIdentifier($tableName),
            implode(', ', $columnsSql)
        ));
    }
}
