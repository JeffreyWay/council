<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();
    }

    /** @test */
    public function an_administrator_can_access_the_user_administration_section()
    {
        $this->signInAdmin()
            ->get(route('admin.users.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function non_administrators_cannot_access_the_user_administration_section()
    {
        $regularUser = create(User::class);

        $this->actingAs($regularUser)
            ->get(route('admin.users.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_lists_all_the_forums_users()
    {
        $users = create(User::class, [], 3);

        $this->signInAdmin($users->first())
            ->get(route('admin.users.index'))
            ->assertViewHas('users', $users->fresh());
    }
}
