<?php

namespace Tests\Feature;

use App\Reputation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReputationTest extends TestCase
{
    use RefreshDatabase;

    protected $points = [];

    /**
     * Fetch current reputation points on class initialization.
     */
    public function setUp()
    {
        parent::setUp();
        $this->points = config('council.reputation');
    }

    /** @test */
    public function a_user_gains_points_when_they_create_a_thread()
    {
        $thread = create(\App\Thread::class);

        $this->assertEquals($this->points['thread_published'], $thread->creator->reputation);
    }

    /** @test */
    public function a_user_lose_points_when_they_delete_a_thread()
    {
        $this->signIn();

        $thread = create(\App\Thread::class, ['user_id' => auth()->id()]);

        $this->assertEquals($this->points['thread_published'], $thread->creator->reputation);

        $this->delete($thread->path());

        $this->assertEquals(0, $thread->creator->fresh()->reputation);
    }

    /** @test */
    public function a_user_gains_points_when_they_reply_to_a_thread()
    {
        $thread = create(\App\Thread::class);

        $reply = $thread->addReply([
            'user_id' => create(\App\User::class)->id,
            'body' => 'Here is a reply.'
        ]);

        $this->assertEquals($this->points['reply_posted'], $reply->owner->reputation);
    }

    /** @test */
    public function a_user_loses_points_when_their_reply_to_a_thread_is_deleted()
    {
        $this->signIn();

        $reply = create(\App\Reply::class, ['user_id' => auth()->id()]);

        $this->assertEquals($this->points['reply_posted'], $reply->owner->reputation);

        $this->delete(route('replies.destroy', $reply->id));

        $this->assertEquals(0, $reply->owner->fresh()->reputation);
    }

    /** @test */
    public function a_user_gains_points_when_their_reply_is_marked_as_best()
    {
        $thread = create(\App\Thread::class);

        $thread->markBestReply($reply = $thread->addReply([
            'user_id' => create(\App\User::class)->id,
            'body' => 'Here is a reply.'
        ]));

        $total = $this->points['reply_posted'] + $this->points['best_reply_awarded'];
        $this->assertEquals($total, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_loses_points_when_their_best_reply_is_deleted()
    {
        $this->signIn();

        $reply = create(\App\Reply::class, ['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $total = $this->points['reply_posted'] + $this->points['best_reply_awarded'];
        $this->assertEquals($total, auth()->user()->fresh()->reputation);

        $reply->delete();

        $this->assertEquals(0, auth()->user()->fresh()->reputation);
    }

    /** @test */
    public function when_a_thread_owner_changes_their_preferred_best_reply_the_points_should_be_transferred()
    {
        // Given a thread exists.
        $thread = create(\App\Thread::class);

        // And we have a user, Jane.
        $jane = create(\App\User::class);

        // If the owner of the thread marks Jane's reply as best...
        $thread->markBestReply($thread->addReply([
            'user_id' => $jane->id,
            'body' => 'Here is a reply.'
        ]));

        // Then Jane should receive the appropriate reputation points.
        $this->assertEquals($this->points['reply_posted'] + $this->points['best_reply_awarded'], $jane->fresh()->reputation);

        // But, if the owner of the thread decides to choose a different best reply, written by John.
        $john = create(\App\User::class);

        $thread->markBestReply($thread->addReply([
            'user_id' => $john->id,
            'body' => 'Here is a better reply.'
        ]));

        // Then, Jane's reputation should be stripped of those "best reply" points.
        $this->assertEquals($this->points['reply_posted'], $jane->fresh()->reputation);

        // And those points should now be reflected on the account of the new best reply owner.
        $total = $this->points['reply_posted'] + $this->points['best_reply_awarded'];
        $this->assertEquals($total, $john->fresh()->reputation);
    }

    /** @test */
    public function a_user_gains_points_when_their_reply_is_favorited()
    {
        // Given we have a signed in user, John.
        $this->signIn($john = create(\App\User::class));

        // And also Jane...
        $jane = create(\App\User::class);

        // If Jane adds a new reply to a thread...
        $reply = create(\App\Thread::class)->addReply([
            'user_id' => $jane->id,
            'body' => 'Some reply'
        ]);

        // And John favorites that reply.
        $this->post(route('replies.favorite', $reply));

        // Then, Jane's reputation should grow, accordingly.
        $this->assertEquals(
            $this->points['reply_posted'] + $this->points['reply_favorited'],
            $jane->fresh()->reputation
        );

        // While John's should remain unaffected.
        $this->assertEquals(0, $john->reputation);
    }

    /** @test */
    public function a_user_loses_points_when_their_favorited_reply_is_unfavorited()
    {
        // Given we have a signed in user, John.
        $this->signIn($john = create(\App\User::class));

        // And also Jane...
        $jane = create(\App\User::class);

        // If Jane adds a new reply to a thread...
        $reply = create(\App\Reply::class, ['user_id' => $jane]);

        // And John favorites that reply.
        $this->post(route('replies.favorite', $reply));

        // Then, Jane's reputation should grow, accordingly.
        $this->assertEquals(
            $this->points['reply_posted'] + $this->points['reply_favorited'],
            $jane->fresh()->reputation
        );

        // But, if John then unfavorites that reply...
        $this->delete(route('replies.unfavorite', $reply));

        // Then, Jane's reputation should be reduced, accordingly.
        $this->assertEquals($this->points['reply_posted'], $jane->fresh()->reputation);

        // While John's should remain unaffected.
        $this->assertEquals(0, $john->reputation);
    }
}
