<?php

namespace MarsBerrys\LaravelSqsPlainQueue\Tests;

use MarsBerrys\LaravelSqsPlainQueue\Tests\Fakes\Job;
use MarsBerrys\LaravelSqsPlainQueue\Tests\Fakes\StandardJob;

class IntegrationTest extends TestCase
{
    public function test_push_to_fifo_queue_returns_id()
    {
        $connection = 'sqs-raw';
        $config = $this->app['config']["queue.connections.{$connection}"];

        if (empty($config['key']) || empty($config['secret']) || empty($config['prefix']) || empty($config['queue']) || empty($config['region'])) {
            return $this->markTestSkipped('SQS config missing key, secret, prefix, queue, or region');
        }

        $id = $this->queue->connection($connection)->push(Job::class, ['with' => 'data']);

        $this->assertNotNull($id);
    }

    public function test_push_standard_job_to_fifo_queue_returns_id()
    {
        $connection = 'sqs-raw';
        $config = $this->app['config']["queue.connections.{$connection}"];

        if (empty($config['key']) || empty($config['secret']) || empty($config['prefix']) || empty($config['queue']) || empty($config['region'])) {
            return $this->markTestSkipped('SQS config missing key, secret, prefix, queue, or region');
        }

        $id = $this->queue->connection($connection)->push(StandardJob::class, ['with' => 'data']);

        $this->assertNotNull($id);
    }

    public function test_push_to_fifo_queue_works_with_alternate_credentials()
    {
        $connection = 'sqs-raw-no-credentials';
        $config = $this->app['config']["queue.connections.{$connection}"];

        if (empty($config['prefix']) || empty($config['queue']) || empty($config['region'])) {
            return $this->markTestSkipped('SQS config missing prefix, queue, or region');
        }

        if (empty(getenv('AWS_ACCESS_KEY_ID')) || empty(getenv('AWS_SECRET_ACCESS_KEY'))) {
            return $this->markTestSkipped('Environment missing alternate SQS credentials');
        }

        $id = $this->queue->connection($connection)->push(Job::class, ['with' => 'data']);

        $this->assertNotNull($id);
    }
}
