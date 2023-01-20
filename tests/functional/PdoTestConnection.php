<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\FunctionalTests;

use Keboola\DbExtractor\Configuration\ValueObject\MysqlDatabaseConfig;
use Keboola\DbExtractor\Extractor\MySQL;
use Keboola\DbExtractor\Extractor\MySQLDbConnectionFactory;
use Keboola\DbExtractorConfig\Configuration\ValueObject\DatabaseConfig;
use PDO;
use Psr\Log\NullLogger;

class PdoTestConnection
{
    public static function getDbConfigArray(): array
    {
        $config = [
            'host' => (string) getenv('DB2_DB_HOST'),
            'port' => (string) getenv('DB2_DB_PORT'),
            'user' => (string) getenv('DB2_DB_USER'),
            '#password' => (string) getenv('DB2_DB_PASSWORD'),
            'database' => (string) getenv('DB2_DB_DATABASE'),
        ];

        return $config;
    }

    public static function createDbConfig(): DatabaseConfig
    {
        $dbConfig = self::getDbConfigArray();
        return DatabaseConfig::fromArray($dbConfig);
    }

    public static function createConnection(): PDO
    {
        $dbConfig = self::createDbConfig();

        $dsn = sprintf(
            'odbc:DRIVER={IBM DB2 ODBC DRIVER};HOSTNAME=%s;PORT=%s;DATABASE=%s;PROTOCOL=TCPIP;',
            $dbConfig->getHost(),
            $dbConfig->getPort(),
            $dbConfig->getDatabase()
        );

        $pdo = new PDO(
            $dsn,
            $dbConfig->getUsername(),
            $dbConfig->getPassword(),
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $pdo->prepare(
            sprintf(
                'SET SCHEMA "%s";',
                getenv('DB2_DB_SCHEMA')
            )
        )->execute();

        return $pdo;
    }
}
