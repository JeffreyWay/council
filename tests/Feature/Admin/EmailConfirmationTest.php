<?php

namespace  Tests\Feature\Admin ;

use App\User;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailConfirmationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();
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
}
