<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\FunctionalTests;

use Keboola\DbExtractor\Extractor\DB2OdbcConnection;
use Keboola\DbExtractor\TraitTests\Tables\AutoIncrementTableTrait;
use Keboola\DbExtractor\TraitTests\Tables\NullableTableTrait;
use Keboola\DbExtractor\TraitTests\Tables\SalesTableTrait;
use Keboola\DbExtractor\TraitTests\Tables\SimpleTableTrait;
use Keboola\DbExtractor\TraitTests\Tables\SpecialTableTrait;
use Keboola\DbExtractor\TraitTests\Tables\TimestampTableTrait;
use PDO;

class DatabaseManager
{
    use SimpleTableTrait;
    use AutoIncrementTableTrait;
    use SalesTableTrait;
    use SpecialTableTrait;
    use TimestampTableTrait;
    use NullableTableTrait;

    protected DB2OdbcConnection $connection;

    public function __construct(DB2OdbcConnection $connection)
    {
        $this->connection = $connection;
    }
}
