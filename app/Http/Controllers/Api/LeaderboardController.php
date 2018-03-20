<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class LeaderboardController extends Controller
{
    public function index()
    {
        return [
            'leaderboard' => User::limit(10)->orderBy('reputation', 'desc')->get()
        ];
    }
}
