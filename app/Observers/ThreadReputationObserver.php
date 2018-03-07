<?php

namespace App\Observers;

use App\Thread;

class ThreadReputationObserver
{
    /**
     * @param Thread $thread
     */
    public function deleting($thread)
    {
        $thread->creator->loseReputation('thread_published');
    }

    /**
     * @param Thread $thread
     */
    public function created($thread)
    {
        $thread->creator->gainReputation('thread_published');
    }
}