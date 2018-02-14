<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = create('App\User');

        $response = $this->getJson("/profiles/{$user->username}")->json();

        $this->assertEquals($response['profileUser']['name'], $user->name);
    }

    /** @test */
    public function profiles_display_all_threads_created_by_the_associated_user()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->get(route('profile', auth()->user()->username))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
