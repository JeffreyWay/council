<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = ['favoritedModel'];

    public function getFavoritedModelAttribute()
    {
        $favoritedModel = null;
        if ($this->subject_type === Favorite::class) {
            $subject = $this->subject()->firstOrFail();
            if ($subject->favorited_type == Reply::class) {
                $favoritedModel = Reply::find($subject->favorited_id);
            }
        }

        return $favoritedModel;
    }

    /**
     * Fetch the associated subject for the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Fetch an activity feed for the given user.
     *
     * @param  User $user
     * @param  int  $take
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function feed($user, $take = 50)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }

    /**
     * Fetch an activity feed for the given user.
     *
     * @param  User $user
     * @param  int  $take
     *
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function paginatedFeed($user)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject');
    }
}
