<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function non_administrators_may_not_lock_threads()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create(\App\Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    function administrators_can_lock_threads()
    {
        $this->signInAdmin();

        $thread = create(\App\Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked, 'Failed asserting that the thread was locked.');
    }

    /** @test */
    function administrators_can_unlock_threads()
    {
        $this->signInAdmin();

        $thread = create(\App\Thread::class, ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked, 'Failed asserting that the thread was unlocked.');
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->signIn();

        $thread = create(\App\Thread::class, ['locked' => true]);

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
