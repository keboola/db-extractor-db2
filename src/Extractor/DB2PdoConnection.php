<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\Extractor;

use Keboola\DbExtractor\Adapter\PDO\PdoConnection;
use Keboola\DbExtractor\Adapter\ValueObject\QueryMetadata;
use Keboola\DbExtractorConfig\Configuration\ValueObject\DatabaseConfig;
use PDOStatement;
use Psr\Log\LoggerInterface;

class DB2PdoConnection extends PdoConnection
{

    public function __construct(LoggerInterface $logger, DatabaseConfig $databaseConfig)
    {
        $dsn = sprintf(
            'odbc:DRIVER={IBM DB2 ODBC DRIVER};HOSTNAME=%s;PORT=%s;DATABASE=%s;PROTOCOL=TCPIP;',
            $databaseConfig->getHost(),
            $databaseConfig->getPort(),
            $databaseConfig->getDatabase()
        );

        parent::__construct(
            $logger,
            $dsn,
            $databaseConfig->getUsername(),
            $databaseConfig->getPassword(),
            []
        );
    }

    public function testConnection(): void
    {
        $this->query('SELECT 1 FROM sysibm.sysdummy1');
    }

    public function quote(string $str): string
    {
        return sprintf('\'%s\'', $str);
    }

    public function quoteIdentifier(string $str): string
    {
        return sprintf('"%s"', $str);
    }

    protected function getQueryMetadata(string $query, PDOStatement $stmt): QueryMetadata
    {
        return new Db2QueryMetadata();
    }
}
