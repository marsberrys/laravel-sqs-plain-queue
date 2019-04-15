<?php

namespace MarsBerrys\LaravelSqsPlainQueue\Tests;

use InvalidArgumentException;
use MarsBerrys\LaravelSqsPlainQueue\Queue\SqsPlainQueue;
use MarsBerrys\LaravelSqsPlainQueue\Queue\Connectors\SqsPlainConnector;

class ConnectorTest extends TestCase
{
    public function test_sqs_fifo_driver_returns_sqs_fifo_queue()
    {
        $config = $this->app['config']['queue.connections.sqs-raw'];
        $connector = new SqsPlainConnector();

        $connection = $connector->connect($config);

        $this->assertInstanceOf(SqsPlainQueue::class, $connection);
    }

    public function test_sqs_fifo_driver_throws_exception_with_invalid_queue_name()
    {
        $config = ['driver' => 'sqs-raw', 'queue' => 'test'];
        $connector = new SqsPlainConnector();

        $this->setExpectedException(InvalidArgumentException::class);

        $connector->connect($config);
    }
}
