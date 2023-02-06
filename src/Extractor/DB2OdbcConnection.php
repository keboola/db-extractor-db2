<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\Extractor;

use Keboola\DbExtractor\Adapter\ODBC\OdbcConnection;
use Keboola\DbExtractorConfig\Configuration\ValueObject\DatabaseConfig;
use Psr\Log\LoggerInterface;

class DB2OdbcConnection extends OdbcConnection
{

    public function __construct(LoggerInterface $logger, DatabaseConfig $databaseConfig)
    {
        $dsn = sprintf(
            'DRIVER=Db2;HOSTNAME=%s;PORT=%s;DATABASE=%s;PROTOCOL=TCPIP;',
            $databaseConfig->getHost(),
            $databaseConfig->getPort(),
            $databaseConfig->getDatabase()
        );

        parent::__construct(
            $logger,
            $dsn,
            $databaseConfig->getUsername(),
            $databaseConfig->getPassword(),
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
}
