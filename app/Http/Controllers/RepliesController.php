<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Http\Requests\CreatePostRequest;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Fetch all relevant replies.
     *
     * @param int    $channelId
     * @param Thread $thread
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(config('council.pagination.perPage'));
    }

    /**
     * Persist a new reply.
     *
     * @param  int           $channelId
     * @param  Thread            $thread
     * @param  CreatePostRequest $form
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        if ($thread->locked) {
            return response('Thread is locked', 422);
        }

        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }

    /**
     * Update an existing reply.
     *
     * @param Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request()->validate(['body' => 'required|spamfree']));
    }

    /**
     * Delete the given reply.
     *
     * @param  Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
