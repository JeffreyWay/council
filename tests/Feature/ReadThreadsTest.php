<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create(\App\Thread::class);
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create(\App\Channel::class);
        $threadInChannel = create(\App\Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(\App\Thread::class);

        $this->get(route('channels', $channel->slug))
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create(\App\User::class, ['username' => 'JohnDoe']));

        $johnsThread = create(\App\Thread::class, ['user_id' => auth()->id()]);
        $janesThread = create(\App\Thread::class);

        $this->get('threads?by=JohnDoe')
            ->assertSee($johnsThread->title)
            ->assertDontSee($janesThread->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = create(\App\Thread::class);
        create(\App\Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create(\App\Thread::class);
        create(\App\Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = create(\App\Thread::class);
        create(\App\Reply::class, ['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }

    /** @test */
    function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create(\App\Thread::class);
        create(\App\Reply::class, ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    function we_record_a_new_visit_each_time_the_thread_is_read()
    {
        $thread = create(\App\Thread::class);

        $this->assertSame(0, $thread->visits);

        $this->call('GET', $thread->path());

        $this->assertEquals(1, $thread->fresh()->visits);
    }
}
