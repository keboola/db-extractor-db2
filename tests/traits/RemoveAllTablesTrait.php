<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\TraitTests;

use PDO;

trait RemoveAllTablesTrait
{
    use QuoteIdentifierTrait;

    protected PDO $connection;

    protected function removeAllTables(): void
    {
        $sql = <<<SQL
SELECT * 
FROM SYSCAT.TABLES 
WHERE OWNERTYPE = 'U' AND TABSCHEMA NOT IN ('DB2INST1', 'SYSTOOLS');
SQL;

        $query = $this->connection->query($sql);
        if (!$query) {
            return;
        }
        $tables = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tables as $table) {
            $dropSql = <<<SQL
DROP TABLE "%s"."%s";
SQL;
            $dropSql = sprintf(
                $dropSql,
                trim($table['TABSCHEMA']),
                trim($table['TABNAME'])
            );

            $this->connection->query($dropSql);
        }
    }
}
