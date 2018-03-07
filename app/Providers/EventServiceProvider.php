<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ThreadReceivedNewReply::class => [
            \App\Listeners\NotifyMentionedUsers::class,
            \App\Listeners\NotifySubscribers::class
        ],

        \App\Events\ThreadWasPublished::class => [
            \App\Listeners\NotifyMentionedUsers::class
        ],
    ];

    /**
     * Use observers to group all of your listeners into a single class
     *
     * @var array
     */
    protected $observers = [
        \App\Thread::class => [
            \App\Observers\GenerateThreadSlugObserver::class,
            \App\Observers\RemoveThreadRepliesObserver::class,
            \App\Observers\ThreadReputationObserver::class,
        ],
        \App\Reply::class => [
            \App\Observers\ReplyReputationObserver::class,
            \App\Observers\ThreadRepliesCountObserver::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        foreach ($this->observers as $model => $observers) {
            foreach ($observers as $observer) {
                $model::observe($observer);
            }
        }
    }
}
