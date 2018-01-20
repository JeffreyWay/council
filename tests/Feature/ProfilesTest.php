<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_has_a_profile()
    {
        $user = create('App\User');

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    /** @test */
    function profiles_display_all_threads_created_by_the_associated_user()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->get(route('profile', auth()->user()->name))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
