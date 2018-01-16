<?php

namespace App\Listeners;

use App\Mentions;
use App\Events\ThreadReceivedNewReply;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        app(Mentions::class)->notifyMentionedUsers($event->reply);
    }
}
