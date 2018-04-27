<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = create(\App\Thread::class);
        $reply = make(\App\Reply::class);

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create(\App\Thread::class);
        $reply = make(\App\Reply::class, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
             ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create(\App\Reply::class);

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create(\App\Reply::class, ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create(\App\Reply::class);

        $this->patch(route('replies.update', $reply->id))
            ->assertRedirect('login');

        $this->signIn()
            ->patch(route('replies.update', $reply->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create(\App\Reply::class, ['user_id' => auth()->id()]);

        $updatedReply = 'You been changed, fool.';
        $this->patch(route('replies.update', $reply->id), ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }

    /** @test */
    function replies_that_contain_spam_may_not_be_created()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create(\App\Thread::class);
        $reply = make(\App\Reply::class, [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    function users_may_only_reply_a_maximum_of_once_per_minute()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create(\App\Thread::class);
        $reply = make(\App\Reply::class);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }
}
