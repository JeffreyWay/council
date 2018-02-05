<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuspendUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();
    }
    
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
