<?php

namespace App\Http\Controllers\Api;

use App\Channel;
use App\Http\Controllers\Controller;

class ChannelsController extends Controller
{
    /**
     * Fetch all channels.
     */
    public function index()
    {
        return cache()->rememberForever('channels', function () {
            return Channel::orderBy('name', 'desc')->get();
        });
    }
}
