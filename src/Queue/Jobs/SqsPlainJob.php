<?php

namespace MarsBerrys\LaravelSqsPlainQueue\Queue\Jobs;

use Aws\Sqs\SqsClient;
use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\SqsJob;
use Illuminate\Contracts\Queue\Job as JobContract;

class SqsPlainJob extends SqsJob
{
    /**
     * The sqs consumer.
     *
     * @var string
     */
    protected $consumer;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \Aws\Sqs\SqsClient  $sqs
     * @param  string  $consumer
     * @param  string  $queue
     * @param  array   $job
     * @return void
     */
    public function __construct(Container $container,
                                SqsClient $sqs,
                                $consumer,
                                $queue,
                                array $job)
    {
        $this->sqs = $sqs;
        $this->job = $job;
        $this->consumer = $consumer;
        $this->queue = $queue;
        $this->container = $container;
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        $jobBody = json_decode($this->job['Body'], true);
        if(isset($jobBody['job'])) {
            // 正常的 Laravel 序列化消息
            return $this->job['Body'];
        }

        // 自定义格式消息
        $job = new $this->consumer(json_decode($this->job['Body'], true));
        $payload = json_encode([
            'job' => 'Illuminate\Queue\CallQueuedHandler@call',
            'data' => [
                'commandName' => get_class($job),
                'command' => serialize(clone $job),
            ],
        ]);

        return $payload;
    }
}
