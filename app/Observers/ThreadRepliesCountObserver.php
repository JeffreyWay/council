<?php

namespace App\Observers;

use App\Reply;

class ThreadRepliesCountObserver
{
    /**
     * @param Reply $reply
     */
    public function created($reply)
    {
        $reply->thread->increment('replies_count');
    }

    /**
     * @param Reply $reply
     */
    public function deleted($reply)
    {
        $reply->thread->decrement('replies_count');
    }
}