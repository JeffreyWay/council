<?php

namespace Tests\Feature;

use App\Channel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChannelAdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();
    }

    /** @test */
    public function an_administrator_can_access_the_channel_administration_section()
    {
        $administrator = factory('App\User')->create();
        config(['council.administrators' => [ $administrator->email ]]);
        $this->signIn($administrator);

        $this->actingAs($administrator)
             ->get('/admin/channels')
             ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function non_administrators_cannot_access_the_channel_administration_section()
    {
        $regularUser = factory(User::class)->create();

        $this->actingAs($regularUser)
             ->get(route('admin.channels.index'))
             ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->actingAs($regularUser)
             ->get(route('admin.channels.create'))
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function an_administrator_can_create_a_channel()
    {
        $response = $this->createChannel([
            'name' => 'php',
            'description' => 'This is the channel for discussing all things PHP.',
        ]);

        $this->get($response->headers->get('Location'))
             ->assertSee('php')
             ->assertSee('This is the channel for discussing all things PHP.');
    }

    /** @test */
    public function a_channel_requires_a_name()
    {
        $this->createChannel(['name' => null])
             ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_channel_requires_a_description()
    {
        $this->createChannel(['description' => null])
             ->assertSessionHasErrors('description');
    }

    protected function createChannel($overrides = [])
    {
        $administrator = factory('App\User')->create();
        config(['council.administrators' => [ $administrator->email ]]);
        $this->signIn($administrator);

        $channel = make(Channel::class, $overrides);

        return $this->post('/admin/channels', $channel->toArray());
    }

}
