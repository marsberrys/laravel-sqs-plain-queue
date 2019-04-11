<?php

namespace MarsBerrys\LaravelSqsRawQueue\Queue\Deduplicators;

use Ramsey\Uuid\Uuid;
use MarsBerrys\LaravelSqsRawQueue\Contracts\Queue\Deduplicator;

class Unique implements Deduplicator
{
    /**
     * Generate a unique deduplication id.
     *
     * This deduplicator should be used for queues that should treat all messages
     * as unique, even if the payload is identical to another message.
     *
     * @param  string  $payload
     * @param  string  $queue
     *
     * @return string
     */
    public function generate($payload, $queue)
    {
        return Uuid::uuid4()->toString();
    }
}
