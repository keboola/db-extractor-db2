<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\TraitTests;

use Keboola\DbExtractor\Extractor\DB2OdbcConnection;

trait RemoveAllTablesTrait
{
    use QuoteIdentifierTrait;

    protected DB2OdbcConnection $connection;

    protected function removeAllTables(): void
    {
        $sql = <<<SQL
SELECT * 
FROM SYSCAT.TABLES 
WHERE OWNERTYPE = 'U' AND TABSCHEMA NOT IN ('DB2INST1', 'SYSTOOLS');
SQL;

        $tables = $this->connection->query($sql)->fetchAll();

        foreach ($tables as $table) {
            $dropSql = <<<SQL
DROP TABLE "%s"."%s";
SQL;
            $dropSql = sprintf(
                $dropSql,
                trim((string) $table['TABSCHEMA']),
                trim((string) $table['TABNAME'])
            );

            $this->connection->query($dropSql);
        }
    }
}
