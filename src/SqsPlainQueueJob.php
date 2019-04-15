<?php
namespace MarsBerrys\LaravelSqsPlainQueue;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use MarsBerrys\LaravelSqsPlainQueue\Bus\SqsPlainQueueable;

class SqsPlainQueueJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, SqsPlainQueueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data = [])
    {
        $this->applyPlainData($data);
    }
}
