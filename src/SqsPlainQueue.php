<?php

namespace MarsBerrys\LaravelSqsPlainQueue;

use Aws\Sqs\SqsClient;
use Illuminate\Queue\SqsQueue;
use Illuminate\Queue\Jobs\SqsJob;
use MarsBerrys\LaravelSqsPlainQueue\Queue\Jobs\SqsPlainJob;

class SqsPlainQueue extends SqsQueue
{
    /**
     * The sqs consumer.
     *
     * @var string
     */
    protected $consumer;

    /**
     * Create a new Amazon SQS queue instance.
     *
     * @param  \Aws\Sqs\SqsClient  $sqs
     * @param  string  $default
     * @param  string  $prefix
     * @return void
     */
    public function __construct(SqsClient $sqs, $default, $prefix = '', $consumer = null)
    {
        $this->sqs = $sqs;
        $this->prefix = $prefix;
        $this->default = $default;
        $this->consumer = $consumer;
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);

        $response = $this->sqs->receiveMessage([
            'QueueUrl' => $queue,
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);

        if (count($response['Messages']) > 0) {
            return new SqsPlainJob($this->container, $this->sqs, $this->consumer, $queue, $response['Messages'][0]);
        }
    }
}
