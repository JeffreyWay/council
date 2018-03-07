<?php

namespace App\Observers;

use App\Thread;

class RemoveThreadRepliesObserver
{
    /**
     * @param Thread $thread
     */
    public function deleting($thread)
    {
        $thread->replies->each->delete();
    }
}