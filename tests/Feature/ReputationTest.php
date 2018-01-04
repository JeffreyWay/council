<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reputation;

class ReputationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_earns_points_when_they_create_a_thread()
    {
        $thread = create('App\Thread');

        $this->assertEquals(Reputation::THREAD_WAS_PUBLISHED, $thread->creator->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_they_reply_to_a_thread()
    {
        $thread = create('App\Thread');

        $reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]);

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_marked_as_best()
    {
        $thread = create('App\Thread');

        $thread->markBestReply($reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]));

        $total = Reputation::REPLY_POSTED + Reputation::BEST_REPLY_AWARDED;
        $this->assertEquals($total, $reply->owner->reputation);
    }
}
