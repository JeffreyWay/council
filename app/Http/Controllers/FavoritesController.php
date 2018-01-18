<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Reputation;

class FavoritesController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new favorite in the database.
     *
     * @param  Reply $reply
     */
    public function store(Reply $reply)
    {
        $reply->favorite();

        $reply->owner->gainReputation('reply_favorited');
    }

    /**
     * Delete the favorite.
     *
     * @param Reply $reply
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        $reply->owner->loseReputation('reply_favorited');
    }
}
