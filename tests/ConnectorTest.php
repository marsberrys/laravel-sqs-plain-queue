<?php

namespace MarsBerrys\LaravelSqsRawQueue\Tests;

use InvalidArgumentException;
use MarsBerrys\LaravelSqsRawQueue\SqsRawQueue;
use MarsBerrys\LaravelSqsRawQueue\Queue\Connectors\SqsRawConnector;

class ConnectorTest extends TestCase
{
    public function test_sqs_fifo_driver_returns_sqs_fifo_queue()
    {
        $config = $this->app['config']['queue.connections.sqs-raw'];
        $connector = new SqsRawConnector();

        $connection = $connector->connect($config);

        $this->assertInstanceOf(SqsRawQueue::class, $connection);
    }

    public function test_sqs_fifo_driver_throws_exception_with_invalid_queue_name()
    {
        $config = ['driver' => 'sqs-raw', 'queue' => 'test'];
        $connector = new SqsRawConnector();

        $this->setExpectedException(InvalidArgumentException::class);

        $connector->connect($config);
    }
}
