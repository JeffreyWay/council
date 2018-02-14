<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ThreadReceivedNewReply implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    /**
     * The reply that was posted.
     *
     * @param \App\Reply $reply
     */
    public $reply;

    /**
     * Create a new event instance.
     *
     * @param \App\Reply $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the subject of the event.
     */
    public function subject()
    {
        return $this->reply;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $channel = $this->channel();

        return new PresenceChannel("forum.{$channel}");
    }

    /**
     * Create a channel name based on path.
     *
     * @return string
     */
    protected function channel()
    {
        $path = str_before($this->reply->path(), '#'); // slice everything before the anchor
        $path = str_replace('/', '-', $path); // replace / with -
        return str_after($path, '-'); // slice everything after the first -
    }
}
