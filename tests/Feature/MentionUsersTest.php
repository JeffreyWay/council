<?php

namespace Tests\Feature;

use App\Mentions;
use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function mentioned_users_in_a_thread_are_notified()
    {
        // Given we have a user, JohnDoe, who is signed in.
        $john = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        // And we also have a user, JaneDoe.
        $jane = create('App\User', ['name' => 'JaneDoe']);

        // And JohnDoe create new thread and mentions @JaneDoe.
        $thread = make('App\Thread', [
            'body' => 'Hey @JaneDoe check this out.'
        ]);

        $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

        // Then @JaneDoe should receive a notification.
        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
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
    }

    /** @test */
    function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'johndoe']);
        create('App\User', ['name' => 'johndoe2']);
        create('App\User', ['name' => 'janedoe']);

        $results = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $results->json());
    }

    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body()
    {
        $thread = new Thread([
            'body' => '@JohnDoe wants to talk to @JaneDoe'
        ]);

        $reply = new Reply([
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JohnDoe', 'JaneDoe'], Mentions::mentionedUsers($thread->body));
        $this->assertEquals(['JaneDoe', 'JohnDoe'], Mentions::mentionedUsers($reply->body));
    }
}
