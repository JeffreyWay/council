<?php

namespace App\Http\Controllers;

class LeaderboardController extends Controller
{
    public function index()
    {
        return view('leaderboard.index');
    }
}
