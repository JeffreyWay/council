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

    /** @test */
    public function an_administrator_can_manually_confirm_a_users_email_address()
    {
        $user = create(User::class, [
            'confirmed' => false
        ]);

        $this->assertFalse($user->confirmed);

        $this->signInAdmin()
            ->patch(route('admin.confirm-user.update', ['id' => $user->id]))
            ->assertRedirect(route('admin.users.index'));

        $this->assertTrue($user->fresh()->confirmed);
    }

    /** @test */
    public function non_administrators_cannot_confirm_a_users_email_address()
    {
        $regularUser = create(User::class);

        $this->actingAs($regularUser)
            ->get(route('admin.users.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    // User Suspension Tests

    /** @test */
    public function administrators_can_suspend_a_users_account()
    {
        $user = create(User::class, ['active' => true]);

        $this->assertTrue($user->active);

        $this->signInAdmin()
            ->delete(route('admin.suspend-user.destroy', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertFalse($user->fresh()->active, 'Failed asserting that the user is suspended.');
    }

    /** @test */
    public function non_administrators_cannot_suspend_a_users_account()
    {
        $user = create(User::class);

        $this->signIn()
            ->delete(route('admin.suspend-user.destroy', $user))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function administrators_can_activate_a_users_account()
    {
        $user = create(User::class, ['active' => false]);

        $this->assertFalse($user->active);

        $this->signInAdmin()
            ->post(route('admin.suspend-user.store', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertTrue($user->fresh()->active, 'Failed asserting that the user is active.');
    }

    /** @test */
    public function non_administrators_cannot_activate_a_users_account()
    {
        $user = create(User::class);

        $this->signIn()
            ->post(route('admin.suspend-user.store', $user))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
