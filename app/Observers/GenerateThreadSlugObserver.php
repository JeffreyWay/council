<?php

namespace App\Observers;

use App\Thread;

class GenerateThreadSlugObserver
{
    /**
     * @param Thread $thread
     */
    public function created($thread)
    {
        $thread->update(['slug' => $thread->title]);
    }
}