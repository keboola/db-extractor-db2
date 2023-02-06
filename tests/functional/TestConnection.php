<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\FunctionalTests;

use Keboola\DbExtractor\Configuration\ValueObject\MysqlDatabaseConfig;
use Keboola\DbExtractor\Extractor\MySQL;
use Keboola\DbExtractor\Extractor\MySQLDbConnectionFactory;
use Keboola\DbExtractorConfig\Configuration\ValueObject\DatabaseConfig;
use PDO;
use Psr\Log\NullLogger;

class TestConnection
{
    public static function getDbConfigArray(): array
    {
        return [
            'host' => (string) getenv('DB2_DB_HOST'),
            'port' => (string) getenv('DB2_DB_PORT'),
            'user' => (string) getenv('DB2_DB_USER'),
            '#password' => (string) getenv('DB2_DB_PASSWORD'),
            'database' => (string) getenv('DB2_DB_DATABASE'),
        ];
    }

    public static function createDbConfig(): DatabaseConfig
    {
        $dbConfig = self::getDbConfigArray();
        return DatabaseConfig::fromArray($dbConfig);
    }
}
