<?php

namespace MarsBerrys\LaravelSqsRawQueue\Tests;

use Illuminate\Container\Container;
use Illuminate\Queue\QueueServiceProvider;
use Illuminate\Events\EventServiceProvider;
use MarsBerrys\LaravelSqsRawQueue\Contracts\Queue\Deduplicator;
use MarsBerrys\LaravelSqsRawQueue\Queue\Connectors\SqsRawConnector;
use MarsBerrys\LaravelSqsRawQueue\LaravelSqsRawQueueServiceProvider;

class ServiceProviderTest extends TestCase
{
    public function test_sqs_fifo_driver_is_registered_with_capsule()
    {
        $connector = $this->callRestrictedMethod($this->queue->getQueueManager(), 'getConnector', ['sqs-raw']);

        $this->assertInstanceOf(SqsRawConnector::class, $connector);
    }

    public function test_unique_deduplicator_is_registered_with_capsule()
    {
        $deduplicator = $this->app->make('queue.sqs-raw.deduplicator.unique');

        $this->assertInstanceOf(Deduplicator::class, $deduplicator);
    }

    public function test_content_deduplicator_is_registered_with_capsule()
    {
        $deduplicator = $this->app->make('queue.sqs-raw.deduplicator.content');

        $this->assertInstanceOf(Deduplicator::class, $deduplicator);
    }

    public function test_sqs_deduplicator_is_registered_with_capsule()
    {
        $deduplicator = $this->app->make('queue.sqs-raw.deduplicator.sqs');

        $this->assertInstanceOf(Deduplicator::class, $deduplicator);
    }

    public function test_sqs_fifo_driver_is_registered_with_laravel_container()
    {
        $container = $this->setup_laravel_container();

        $connector = $this->callRestrictedMethod($container['queue'], 'getConnector', ['sqs-raw']);

        $this->assertInstanceOf(SqsRawConnector::class, $connector);
    }

    public function test_unique_deduplicator_is_registered_with_laravel_container()
    {
        $container = $this->setup_laravel_container();

        $deduplicator = $container->make('queue.sqs-raw.deduplicator.unique');

        $this->assertInstanceOf(Deduplicator::class, $deduplicator);
    }

    public function test_content_deduplicator_is_registered_with_laravel_container()
    {
        $container = $this->setup_laravel_container();

        $deduplicator = $container->make('queue.sqs-raw.deduplicator.content');

        $this->assertInstanceOf(Deduplicator::class, $deduplicator);
    }

    public function test_sqs_deduplicator_is_registered_with_laravel_container()
    {
        $container = $this->setup_laravel_container();

        $deduplicator = $container->make('queue.sqs-raw.deduplicator.sqs');

        $this->assertInstanceOf(Deduplicator::class, $deduplicator);
    }

    public function test_reversed_registration_still_works()
    {
        $container = new Container();

        // Only register the queue manager to avoid events dependency.
        (new LaravelSqsRawQueueServiceProvider($container))->register();
        $this->callRestrictedMethod(new QueueServiceProvider($container), 'registerManager');

        $connector = $this->callRestrictedMethod($container['queue'], 'getConnector', ['sqs-raw']);

        $this->assertInstanceOf(SqsRawConnector::class, $connector);
    }

    protected function setup_laravel_container()
    {
        $container = new Container();

        // Only register the queue manager to avoid events dependency.
        $this->callRestrictedMethod(new QueueServiceProvider($container), 'registerManager');
        (new LaravelSqsRawQueueServiceProvider($container))->register();

        return $container;
    }
}
