<?php

namespace Tests\Feature;

use App\Activity;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase, MockeryPHPUnitIntegration;

    public function setUp()
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->post(route('threads'))->assertStatus(Response::HTTP_FOUND)->assertRedirect(route('login'));
    }

    /** @test */
    function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory(\App\User::class)->states('unconfirmed')->create();

        $this->signIn($user);

        $thread = make(\App\Thread::class);

        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'You must first confirm your email address.');
    }

    /** @test */
    function a_user_can_create_new_forum_threads()
    {
        $response = $this->publishThread(['title' => 'Some Title', 'body' => 'Some body.']);

        $this->get($response->headers->get('Location'))
            ->assertSee('Some Title')
            ->assertSee('Some body.');
    }

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_recaptcha_verification()
    {
        if (Recaptcha::isInTestMode()) {
            $this->markTestSkipped("Recaptcha is in test mode.");
        }

        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory(\App\Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = create(\App\Thread::class, ['title' => 'Foo Title']);

        $this->assertEquals($thread->slug, 'foo-title');

        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        $thread = create(\App\Thread::class, ['title' => 'Some Title 24']);

        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create(\App\Thread::class);

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create(\App\Thread::class, ['user_id' => auth()->id()]);
        $reply = create(\App\Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    /** @test */
    public function a_new_thread_cannot_be_created_in_an_archived_channel()
    {
        $channel = factory(\App\Channel::class)->create(['archived' => true]);

        $this->publishThread(['channel_id' => $channel->id])
            ->assertSessionHasErrors('channel_id');

        $this->assertCount(0, $channel->threads);
    }

    protected function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make(\App\Thread::class, $overrides);

        return $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);
    }
}
