<?php

declare(strict_types=1);

use Keboola\DbExtractor\FunctionalTests\DatabaseManager;
use Keboola\DbExtractor\FunctionalTests\DatadirTest;

return function (DatadirTest $test): void {
    $manager = new DatabaseManager($test->getConnection());

    // simple empty table
    $manager->createSimpleTable();

    // sales table
    $manager->createSalesTable();

    // special table
    $manager->createSpecialTable();
};
