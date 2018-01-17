<?php

namespace Tests\Feature;

use App\Thread;
use App\Mentions;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_thread_are_notified()
    {
        // Given we have a user, JohnDoe, who is signed in.
        $john = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        // And we also have a user, JaneDoe.
        $jane = create('App\User', ['name' => 'JaneDoe']);

        // And JohnDoe create a new thread and mentions @JaneDoe.
        $thread = make('App\Thread', [
            'body' => 'Hey @JaneDoe check this out.'
        ]);

        $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

        // Then @JaneDoe should receive a notification.
        $this->assertCount(1, $jane->notifications);

        $this->assertEquals(
            "JohnDoe mentioned you in \"{$thread->title}\"",
            $jane->notifications->first()->data['message']
        );
    }

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        // Given we have a user, JohnDoe, who is signed in.
        $john = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        // And we also have a user, JaneDoe.
        $jane = create('App\User', ['name' => 'JaneDoe']);

        // If we have a thread
        $thread = create('App\Thread');

        // And JohnDoe replies to that thread and mentions @JaneDoe.
        $reply = make('App\Reply', [
            'body' => 'Hey @JaneDoe check this out.'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        // Then @JaneDoe should receive a notification.
        $this->assertCount(1, $jane->notifications);

        $this->assertEquals(
            "JohnDoe mentioned you in \"{$thread->title}\"",
            $jane->notifications->first()->data['message']
        );
    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'johndoe']);
        create('App\User', ['name' => 'johndoe2']);
        create('App\User', ['name' => 'janedoe']);

        $results = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $results->json());
    }
}
