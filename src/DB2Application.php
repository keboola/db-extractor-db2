<?php

declare(strict_types=1);

namespace Keboola\DbExtractor;

use Keboola\DbExtractorConfig\Config;
use Keboola\DbExtractorConfig\Configuration\ActionConfigRowDefinition;
use Keboola\DbExtractorConfig\Configuration\ConfigRowDefinition;

class DB2Application extends Application
{
    protected function loadConfig(): void
    {
        $config = $this->getRawConfig();
        $action = $config['action'] ?? 'run';

        $config['parameters']['extractor_class'] = 'DB2';
        $config['parameters']['data_dir'] = $this->getDataDir();

        if ($action === 'run') {
            $this->config = new Config($config, new ConfigRowDefinition());
        } else {
            $this->config = new Config($config, new ActionConfigRowDefinition());
        }
    }
}
