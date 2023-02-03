<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\Extractor;

use Iterator;
use Keboola\Csv\CsvWriter;
use Keboola\DbExtractor\Adapter\ResultWriter\DefaultResultWriter;
use Keboola\DbExtractor\Adapter\ValueObject\QueryMetadata;
use Keboola\DbExtractorConfig\Configuration\ValueObject\ExportConfig;

class DB2ResultWriter extends DefaultResultWriter
{
    protected function writeRows(
        Iterator $iterator,
        QueryMetadata $queryMetadata,
        ExportConfig $exportConfig,
        CsvWriter $csvWriter
    ): void {
        // Write the rest
        $this->rowsCount = 0;
        $this->lastRow = null;
        while ($iterator->valid()) {
            $resultRow = (array) $iterator->current();
            // Write header
            if ($this->rowsCount === 0 && $this->hasCsvHeader($exportConfig)) {
                $this->writeRow(array_keys($resultRow), $csvWriter);
            }
            $this->writeRow($resultRow, $csvWriter);
            $iterator->next();

            $this->lastRow = $resultRow;
            $this->rowsCount++;
        }
    }
}
