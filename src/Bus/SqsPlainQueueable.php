<?php

namespace MarsBerrys\LaravelSqsPlainQueue\Bus;

trait SqsPlainQueueable
{
    /**
     * The message group id the job should be sent to.
     *
     * @var string
     */
    public $messageGroupId;

    /**
     * The deduplication method to use for the job.
     *
     * @var string
     */
    public $deduplicator;

    /**
     * The plain data for the job.
     *
     * @var string
     */
    protected $plainData;


    /**
     * Set the desired message group id for the job.
     *
     * @param  string  $messageGroupId
     *
     * @return $this
     */
    public function onMessageGroup($messageGroupId)
    {
        $this->messageGroupId = $messageGroupId;

        return $this;
    }

    /**
     * Set the desired deduplication method for the job.
     *
     * @param  string  $deduplicator
     *
     * @return $this
     */
    public function withDeduplicator($deduplicator)
    {
        $this->deduplicator = $deduplicator;

        return $this;
    }

    /**
     * Remove the deduplication method from the job.
     *
     * @return $this
     */
    public function withoutDeduplicator()
    {
        return $this->withDeduplicator('');
    }

    /**
     * Get plain data of the job
     * @param  string|null $key
     * @param  mixed       $default
     * @return mixed
     */
    public function getPlainData(string $key = null, $default = null)
    {
        return array_get($this->plainData, $key, $default);
    }

    /**
     * Apply plain data for the job
     * @param  array|null $data
     * @return $this
     */
    public function applyPlainData(array $data = [])
    {
        $this->plainData = $data;
        if($this->plainData) {
            foreach ($this->plainData as $key => $value) {
                if(property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
        return $this;
    }
}
