<?php

namespace MarsBerrys\LaravelSqsPlainQueue\Queue\Connectors;

use Aws\Sqs\SqsClient;
use InvalidArgumentException;
use Illuminate\Queue\Connectors\SqsConnector;
use Illuminate\Support\Arr;
use MarsBerrys\LaravelSqsPlainQueue\Queue\SqsPlainQueue;

class SqsPlainConnector extends SqsConnector
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

        if ($config['key'] && $config['secret']) {
            $config['credentials'] = Arr::only($config, ['key', 'secret']);
        }

        return new SqsPlainQueue(
            new SqsClient($config), $config['queue'], Arr::get($config, 'prefix', ''), $config['consumer']
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
