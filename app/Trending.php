<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * Fetch all trending threads.
     *
     * @return array
     */
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    /**
     * Push a new thread to the trending list.
     *
     * @param Thread $thread
     */
    public function push($thread, $increment = 1)
    {
        Redis::zincrby($this->cacheKey(), $increment, $this->encode($thread));
    }

    /**
     * Get the trending score of the given thread.
     *
     * @param int
     */
    public function score($thread)
    {
        return Redis::zScore($this->cacheKey(), $this->encode($thread));
    }

    /**
     * Reset all trending threads.
     */
    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    /**
     * Get the encoded data for a given thread.
     *
     * @param int
     */
    private function encode($thread)
    {
        return json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]);
    }

    /**
     * Get the cache key name.
     *
     * @return string
     */
    private function cacheKey()
    {
        return app()->environment('testing')
            ? 'testing_trending_threads'
            : 'trending_threads';
    }
}
