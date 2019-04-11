<?php

namespace MarsBerrys\LaravelSqsRawQueue\Queue\Connectors;

use Aws\Sqs\SqsClient;
use InvalidArgumentException;
use Illuminate\Queue\Connectors\SqsConnector;
use MarsBerrys\LaravelSqsRawQueue\SqsRawQueue;

class SqsRawConnector extends SqsConnector
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     *
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);

        if (!ends_with($config['queue'], '.fifo')) {
            throw new InvalidArgumentException('FIFO queue name must end in ".fifo"');
        }

        if (!empty($config['key']) && !empty($config['secret'])) {
            $config['credentials'] = array_only($config, ['key', 'secret']);
        }

        $group = array_pull($config, 'group', 'default');
        $deduplicator = array_pull($config, 'deduplicator', 'unique');

        return new SqsRawQueue(
            new SqsClient($config),
            $config['queue'],
            array_get($config, 'prefix', ''),
            $group,
            $deduplicator
        );
    }

    /**
     * Get the default configuration for SQS.
     *
     * @param  array  $config
     *
     * @return array
     */
    protected function getDefaultConfiguration(array $config)
    {
        // Laravel >= 5.1 has the "getDefaultConfiguration" method.
        if (method_exists(get_parent_class(), 'getDefaultConfiguration')) {
            return parent::getDefaultConfiguration($config);
        }

        return array_merge([
            'version' => 'latest',
            'http' => [
                'timeout' => 60,
                'connect_timeout' => 60,
            ],
        ], $config);
    }
}
