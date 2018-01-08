<?php

namespace Tests\Feature\admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AdministratorTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();
    }

    /** @test */
    public function an_administrator_can_access_the_administration_section()
    {
        $administrator = factory(User::class)->states('administrator')->create();

        $this->actingAs($administrator)
             ->get('/admin')
             ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_non_administrator_cannot_access_the_administration_section()
    {
        $regularUser = factory(User::class)->create();

        $this->actingAs($regularUser)
             ->get('/admin')
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
