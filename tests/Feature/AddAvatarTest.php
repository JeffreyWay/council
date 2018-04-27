<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_members_can_add_avatars()
    {
        $this->withExceptionHandling();

        $this->json('POST', 'api/users/1/avatar')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->withExceptionHandling()->signIn();

        $this->json('POST', route('avatar', auth()->id()), [
            'avatar' => 'not-an-image'
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $this->json('POST', route('avatar', auth()->id()), [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset('avatars/'.$file->hashName()), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
