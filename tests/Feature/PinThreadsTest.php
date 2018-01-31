<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PinThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function administrators_can_pin_threads()
    {
        $this->signInAdmin();

        $thread = create('App\Thread');

        $this->post(route('pinned-threads.store', $thread));

        $this->assertTrue($thread->fresh()->pinned, 'Failed asserting that the thread was pinned.');
    }

    /** @test */
    public function administrators_can_unpin_threads()
    {
        $this->signInAdmin();

        $thread = create('App\Thread', ['pinned' => true]);

        $this->delete(route('pinned-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->pinned, 'Failed asserting that the thread was unlocked.');
    }

    /** @test */
    public function pinned_threads_are_listed_first()
    {
        $channel = create(Channel::class, [
            'name' => 'PHP',
            'slug' => 'php'
        ]);

        create(Thread::class, ['channel_id' => $channel->id]);
        create(Thread::class, ['channel_id' => $channel->id]);
        $threadToPin = create(Thread::class, ['channel_id' => $channel->id]);

        $this->signInAdmin();

        $response = $this->getJson(route('threads'));
        $response->assertJson([
            'data' => [
                ['id' => '1'],
                ['id' => '2'],
                ['id' => '3'],
            ]
        ]);

        $this->post(route('pinned-threads.store', $threadToPin));

        $response = $this->getJson(route('threads'));
        $response->assertJson([
            'data' => [
                ['id' => '3'],
                ['id' => '1'],
                ['id' => '2'],
            ]
        ]);

    }
}
