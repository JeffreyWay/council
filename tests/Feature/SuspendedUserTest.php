<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuspendedUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function suspended_users_may_not_create_threads()
    {
        $this->withExceptionHandling()
            ->signInSuspended();

        $this->get('/threads/create')
            ->assertRedirect(route('threads'))
            ->assertSessionHas('warning');

        $this->post(route('threads'))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('warning');
    }

    /** @test */
    public function suspended_users_may_not_add_replies()
    {
        $thread = create('App\Thread');
        $reply = make('App\Reply');

        $this->withExceptionHandling()
            ->signInSuspended();

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('warning');

        $this->postJSON($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure(['reason']);
    }
}
