<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\Extractor;

use Keboola\Datatype\Definition\GenericStorage;
use Keboola\DbExtractor\Adapter\ExportAdapter;
use Keboola\DbExtractor\Adapter\Metadata\MetadataProvider;
use Keboola\DbExtractor\Adapter\PDO\PdoExportAdapter;
use Keboola\DbExtractor\Adapter\Query\DefaultQueryFactory;
use Keboola\DbExtractor\Exception\UserException;
use Keboola\DbExtractor\TableResultFormat\Exception\ColumnNotFoundException;
use Keboola\DbExtractorConfig\Configuration\ValueObject\DatabaseConfig;
use Keboola\DbExtractorConfig\Configuration\ValueObject\ExportConfig;

class DB2 extends BaseExtractor
{
    public const INCREMENTAL_TYPES = [
        'BIGINT',
        'SMALLINT',
        'INT',
        'INTEGER',
        'NUMERIC',
        'DECIMAL',
        'DECFLOAT',
        'DOUBLE',
        'REAL',
        'TIMESTAMP',
        'DATE',
    ];

    private DB2PdoConnection $connection;

    public function testConnection(): void
    {
        $this->connection->testConnection();
    }

    protected function createConnection(DatabaseConfig $databaseConfig): void
    {
        $this->connection = new DB2PdoConnection($this->logger, $databaseConfig);
    }

    protected function createExportAdapter(): ExportAdapter
    {
        $resultWriter = new DB2ResultWriter($this->state);
        $simpleQueryFactory = new DefaultQueryFactory($this->state);

        return new PdoExportAdapter(
            $this->logger,
            $this->connection,
            $simpleQueryFactory,
            $resultWriter,
            $this->dataDir,
            $this->state
        );
    }

    protected function createMetadataProvider(): MetadataProvider
    {
        return new DB2MetadataProvider($this->connection);
    }

    protected function validateIncrementalFetching(ExportConfig $exportConfig): void
    {
        try {
            $column = $this
                ->getMetadataProvider()
                ->getTable($exportConfig->getTable())
                ->getColumns()
                ->getByName($exportConfig->getIncrementalFetchingColumn());
        } catch (ColumnNotFoundException $e) {
            throw new UserException(
                sprintf(
                    'Column "%s" specified for incremental fetching was not found in the table',
                    $exportConfig->getIncrementalFetchingColumn()
                )
            );
        }

        $datatype = new GenericStorage($column->getType());
        if (!in_array($datatype->getType(), self::INCREMENTAL_TYPES, true)) {
            throw new UserException(sprintf(
                'Unexpected type "%s" of incremental fetching column "%s". Expected types: %s.',
                $column->getType(),
                $column->getName(),
                implode(', ', self::INCREMENTAL_TYPES),
            ));
        }
    }

    protected function getMaxOfIncrementalFetchingColumn(ExportConfig $exportConfig): ?string
    {
        $sql = 'SELECT MAX(%s) as %s FROM %s.%s';
        $fullsql = sprintf(
            $sql,
            $this->connection->quoteIdentifier($exportConfig->getIncrementalFetchingConfig()->getColumn()),
            $this->connection->quoteIdentifier($exportConfig->getIncrementalFetchingConfig()->getColumn()),
            $this->connection->quoteIdentifier($exportConfig->getTable()->getSchema()),
            $this->connection->quoteIdentifier($exportConfig->getTable()->getName())
        );
        $result = $this->connection->query($fullsql)->fetchAll();

        return $result ? (string) $result[0][$exportConfig->getIncrementalFetchingColumn()] : null;
    }
}
