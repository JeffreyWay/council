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

        Reputation::gain($reply->owner, Reputation::REPLY_FAVORITED);
    }

    /**
     * Delete the favorite.
     *
     * @param Reply $reply
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        Reputation::lose($reply->owner, Reputation::REPLY_FAVORITED);
    }
}
