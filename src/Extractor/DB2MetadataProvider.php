<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\Extractor;

use Keboola\DbExtractor\Adapter\Metadata\MetadataProvider;
use Keboola\DbExtractor\Exception\UserException;
use Keboola\DbExtractor\TableResultFormat\Metadata\Builder\ColumnBuilder;
use Keboola\DbExtractor\TableResultFormat\Metadata\Builder\MetadataBuilder;
use Keboola\DbExtractor\TableResultFormat\Metadata\Builder\TableBuilder;
use Keboola\DbExtractor\TableResultFormat\Metadata\ValueObject\Table;
use Keboola\DbExtractor\TableResultFormat\Metadata\ValueObject\TableCollection;
use Keboola\DbExtractorConfig\Configuration\ValueObject\InputTable;
use PDOException;

class DB2MetadataProvider implements MetadataProvider
{
    private DB2OdbcConnection $connection;

    public function __construct(DB2OdbcConnection $connection)
    {
        $this->connection = $connection;
    }

    public function getTable(InputTable $table): Table
    {
        return $this
            ->listTables([$table])
            ->getByNameAndSchema($table->getName(), $table->getSchema());
    }

    public function listTables(array $whitelist = [], bool $loadColumns = true): TableCollection
    {
        $tableBuilders = [];
        // Process tables
        $builder = MetadataBuilder::create();

        $nameTables = [];
        foreach ($this->queryTables($whitelist) as $item) {
            $tableId = $item['TABSCHEMA'] . '.' . $item['TABNAME'];
            $tableBuilder = $builder->addTable();
            $tableBuilders[$tableId] = $tableBuilder;

            if ($loadColumns === false) {
                $tableBuilder->setColumnsNotExpected();
            }

            $this->processTableData($tableBuilder, $item);
            $nameTables[] = $item['TABNAME'];
        }

        if ($loadColumns) {
            foreach ($this->queryColumns($nameTables) as $column) {
                $tableId = $column['TABSCHEMA'] . '.' . $column['TABNAME'];

                if (!isset($tableBuilders[$tableId])) {
                    continue;
                }
                $columnBuilder = $tableBuilders[$tableId]->addColumn();

                $this->processColumnData($columnBuilder, $column);
            }
        }

        return $builder->build();
    }

    private function queryTables(array $whitelist = []): iterable
    {
        $sql[] = 'SELECT *';
        $sql[] = 'FROM SYSCAT.TABLES';
        $sql[] = 'WHERE OWNERTYPE = \'U\'';

        if ($whitelist) {
            $sql[] = sprintf(
                'AND TABNAME IN (%s) AND TABSCHEMA IN (%s)',
                implode(',', array_map(
                    fn (InputTable $table) => $this->connection->quote($table->getName()),
                    $whitelist
                )),
                implode(',', array_map(
                    fn (InputTable $table) => $this->connection->quote($table->getSchema()),
                    $whitelist
                )),
            );
        } else {
            $sql[] = 'AND TABSCHEMA NOT IN (\'DB2INST1\', \'SYSTOOLS\')';
        }
        $sql[] = ' ORDER BY TABNAME';

        // Run query
        return $this->queryAndFetchAll(implode(' ', $sql));
    }

    private function queryColumns(array $tableList = []): iterable
    {
        $sqlTemplate = <<<SQL
SELECT COLS.*, IDXCOLS.INDEXTYPE, IDXCOLS.UNIQUERULE,REFCOLS.REFKEYNAME, REFCOLS.REFTABNAME
FROM SYSCAT.COLUMNS AS COLS 
LEFT OUTER JOIN (
    SELECT ICU.COLNAME, IDX.TABNAME, IDX.INDEXTYPE, IDX.UNIQUERULE FROM SYSCAT.INDEXCOLUSE AS ICU
    JOIN SYSCAT.INDEXES AS IDX 
    ON ICU.INDNAME = IDX.INDNAME AND SUBSTR(IDX.INDEXTYPE,1,1) != 'X'
) AS IDXCOLS ON COLS.TABNAME = IDXCOLS.TABNAME AND COLS.COLNAME = IDXCOLS.COLNAME
LEFT OUTER JOIN (
    SELECT KCU.COLNAME, REF.TABNAME, REF.REFKEYNAME, REF.REFTABNAME FROM SYSCAT.KEYCOLUSE AS KCU
    JOIN SYSCAT.REFERENCES AS REF 
    ON KCU.CONSTNAME = REF.CONSTNAME
) AS REFCOLS ON COLS.TABNAME = REFCOLS.TABNAME AND COLS.COLNAME = REFCOLS.COLNAME 
WHERE COLS.TABNAME IN (%s) ORDER BY COLS.TABSCHEMA, COLS.TABNAME, COLS.COLNO
SQL;
        $sql = sprintf(
            $sqlTemplate,
            implode(',', array_map(
                fn (string $tableName) => $this->connection->quote($tableName),
                $tableList
            ))
        );

        return $this->queryAndFetchAll($sql);
    }

    private function queryAndFetchAll(string $sql): iterable
    {
        $result = $this->connection->query($sql);

        try {
            while ($row = $result->fetch()) {
                yield $row;
            }
        } catch (PDOException $e) {
            throw new UserException(sprintf('Cannot load DB metadata: %s', $e->getMessage()));
        }
    }

    private function processTableData(TableBuilder $builder, array $data): void
    {
        $tableType = match ($data['TYPE']) {
            'T', 'U' => 'table',
            'V', 'W' => 'view',
            default => $data['TYPE'],
        };

        $builder
            ->setName($data['TABNAME'])
            ->setSchema($data['TABSCHEMA'])
            ->setType($tableType);
    }

    private function processColumnData(ColumnBuilder $columnBuilder, array $column): void
    {
        $type = $column['TYPENAME'];
        $length = $column['LENGTH'];
        if ($column['SCALE'] !== 0 && $column['TYPENAME'] === 'DECIMAL') {
            $length .= ',' . $column['SCALE'];
        }
        if (!in_array($type, DB2::FIXED_LENGTH_TYPES, true)) {
            $columnBuilder->setLength($length);
        }

        $columnBuilder
            ->setName((string) $column['COLNAME'])
            ->setType($type)
            ->setDefault((string) $column['DEFAULT'])
            ->setNullable(!(($column['NULLS'] === 'N')))
            ->setOrdinalPosition(((int) $column['COLNO'])+1)
            ->setPrimaryKey($column['UNIQUERULE'] === 'P');

        if (!is_null($column['INDEXTYPE'])) {
            $columnBuilder->setUniqueKey($column['UNIQUERULE'] === 'U');
        }
    }
}
