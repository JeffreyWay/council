<?php

namespace App;

use Illuminate\Support\Facades\Cache;

class Trending
{
    /**
     * Fetch all trending threads.
     *
     * @return array
     */
    public function get()
    {
        return Cache::get($this->cacheKey(), collect())
                    ->sortByDesc('score')
                    ->slice(0, 5)
                    ->values();
    }

    /**
     * Push a new thread to the trending list.
     *
     * @param Thread $thread
     */
    public function push($thread, $increment = 1)
    {
        $trending = Cache::get($this->cacheKey(), collect());

        $trending[$thread->id] = (object)[
            'score' => $this->score($thread) + $increment,
            'title' => $thread->title,
            'path' => $thread->path(),
        ];

        Cache::forever($this->cacheKey(), $trending);
    }

    /**
     * Get the trending score of the given thread.
     *
     * @param int
     */
    public function score($thread)
    {
        $trending = Cache::get($this->cacheKey(), collect());

        if(! isset($trending[$thread->id])) {
            return 0;
        }

        return $trending[$thread->id]->score;
    }

    /**
     * Reset all trending threads.
     */
    public function reset()
    {
        return Cache::forget($this->cacheKey());
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
