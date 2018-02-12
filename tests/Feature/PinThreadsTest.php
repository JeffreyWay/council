<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PinThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function regular_users_cannot_pin_threads()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('pinned-threads.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->pinned, 'Failed asserting that the thread was unpinned.');
    }

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

        $this->assertFalse($thread->fresh()->pinned, 'Failed asserting that the thread was unpinned.');
    }

    /** @test */
    public function pinned_threads_are_listed_first()
    {
        $this->signInAdmin();

        $threads = create(Thread::class, [], 3);
        $ids = $threads->pluck('id');

        $response_data = $this->getJson(route('threads'))->decodeResponseJson()['data'];
        $this->assertEquals($ids[0], $response_data[0]['id']);
        $this->assertEquals($ids[1], $response_data[1]['id']);
        $this->assertEquals($ids[2], $response_data[2]['id']);

        $this->post(route('pinned-threads.store', $pinned = $threads->last()));

        $response_data = $this->getJson(route('threads'))->decodeResponseJson()['data'];
        $this->assertEquals($pinned->id, $response_data[0]['id']);
        $this->assertEquals($ids[0], $response_data[1]['id']);
        $this->assertEquals($ids[1], $response_data[2]['id']);
    }
}
