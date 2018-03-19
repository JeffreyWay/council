<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\ThreadReceivedNewReply;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create(\App\Thread::class);
    }

    /** @test */
    function a_thread_has_a_path()
    {
        $thread = create(\App\Thread::class);

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->slug}",
            $thread->path()
        );
    }

    /** @test */
    function a_thread_has_a_creator()
    {
        $this->assertInstanceOf(\App\User::class, $this->thread->creator);
    }

    /** @test */
    function a_thread_has_replies()
    {
        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $this->thread->replies
        );
    }

    /** @test */
    function a_thread_can_have_a_best_reply()
    {
        $reply = $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->thread->markBestReply($reply);

        $this->assertEquals($reply->id, $this->thread->bestReply->id);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar',
                'user_id' => create(\App\User::class)->id
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    function a_thread_belongs_to_a_channel()
    {
        $thread = create(\App\Thread::class);

        $this->assertInstanceOf(\App\Channel::class, $thread->channel);
    }

    /** @test */
    function a_thread_can_be_subscribed_to()
    {
        $thread = create(\App\Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from()
    {
        $thread = create(\App\Thread::class);

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create(\App\Thread::class);

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create(\App\Thread::class);

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    /** @test */
    function a_threads_body_is_sanitized_automatically()
    {
        $thread = make(\App\Thread::class, ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);

        $this->assertEquals("<p>This is okay.</p>", $thread->body);
        $this->assertEquals('<p>This is okay.</p>', $thread->body);
    }

    /** @test */
    public function an_event_is_fired_when_a_reply_is_added_to_a_thread()
    {
        Event::fake();

        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        Event::assertDispatched(ThreadReceivedNewReply::class);
    }
}
