<?php

namespace App\Http\Controllers;

use App\Thread;

class PinnedThreadsController extends Controller
{
    /**
     * Pin the given thread.
     *
     * @param \App\Thread $thread
     */
    public function store(Thread $thread)
    {
        $thread->update(['pinned' => true]);
    }

    /**
     * Un-Pin the given thread.
     *
     * @param \App\Thread $thread
     */
    public function destroy(Thread $thread)
    {
        $thread->update(['pinned' => false]);
    }
}
