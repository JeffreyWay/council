<?php

namespace Tests\Unit;

use App\Reply;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_an_owner()
    {
        $reply = create(\App\Reply::class);

        $this->assertInstanceOf(\App\User::class, $reply->owner);
    }

    /** @test */
    function it_knows_if_it_was_just_published()
    {
        $reply = create(\App\Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new Reply([
            'body' => 'Hello @Jane-Doe.'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.',
            $reply->body
        );
    }

    /** @test */
    function it_knows_if_it_is_the_best_reply()
    {
        $reply = create(\App\Reply::class);

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
    function a_reply_body_is_sanitized_automatically()
    {
        $reply = make(\App\Reply::class, ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);

        $this->assertEquals("<p>This is okay.</p>", $reply->body);
    }

    /** @test */
    function a_reply_knows_the_total_xp_earned()
    {
        $this->signIn();

        $reply = create('App\Reply'); // 2 points for creating the reply.

        $this->assertEquals(2, $reply->xp);

        $reply->thread->markBestReply($reply); // 50 points for best.

        $this->assertEquals(52, $reply->xp);

        $this->post(route('replies.favorite', $reply)); // 5 points for favoriting.

        $this->assertEquals(57, $reply->xp);
    }
}
