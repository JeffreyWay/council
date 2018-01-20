<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ThreadWasPublished
{
    use SerializesModels;

    /**
     * The thread that was published.
     *
     * @var \App\Thread
     */
    public $thread;

    /**
     * Create a new event instance.
     *
     * @param \App\Thread $thread
     * @return void
     */
    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * Get the subject of the event.
     */
    public function subject()
    {
        return $this->thread;
    }
}
