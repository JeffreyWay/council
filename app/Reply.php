<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['owner', 'favorites'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest', 'xp', 'path'];

    /**
     * Boot the reply instance.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');

            $reply->owner->gainReputation('reply_posted');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');

            $reply->owner->loseReputation('reply_posted');

            if ($reply->isBest()) {
                $reply->owner->loseReputation('best_reply_awarded');
            }
        });
    }

    /**
     * A reply has an owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply belongs to a thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Get the related title for the reply.
     */
    public function title()
    {
        return $this->thread->title;
    }

    /**
     * Determine if the reply was just published a moment ago.
     *
     * @return bool
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     * Determine the path to the reply.
     *
     * @return string
     */
    public function path()
    {
        return $this->thread->path()."#reply-{$this->id}";
    }

    /**
     * Fetch the path to the thread as a property.
     */
    public function getPathAttribute()
    {
        return $this->path();
    }

    /**
     * Access the body attribute.
     *
     * @param  string $body
     * @return string
     */
    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }

    /**
     * Set the body attribute.
     *
     * @param string $body
     */
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace(
            '/@([\w\-]+)/',
            '<a href="/profiles/$1">$0</a>',
            $body
        );
    }

    /**
     * Determine if the current reply is marked as the best.
     *
     * @return bool
     */
    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    /**
     * Determine if the current reply is marked as the best.
     *
     * @return bool
     */
    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    /**
     * Calculate the correct XP amount earned for the current reply.
     */
    public function getXpAttribute()
    {
        $xp = config('council.reputation.reply_posted');

        if ($this->isBest()) {
            $xp += config('council.reputation.best_reply_awarded');
        }

        return $xp += $this->favorites()->count() * config('council.reputation.reply_favorited');
    }
}
