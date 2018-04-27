<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_retrieves_the_top_10_users_sorted_by_reputation()
    {
        collect([1, 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100])->each(function ($reputation) {
            create(User::class, [
                'reputation' => $reputation
            ]);
        });

        $reputation = collect($this->getJson(route('api.leaderboard.index'))->json()['leaderboard'])
            ->pluck('reputation')
            ->toArray();

        $this->assertEquals([100, 90, 80, 70, 60, 50, 40, 30, 20, 10], $reputation);
    }

    /** @test */
    public function users_can_access_the_forum_leaderboard_page()
    {
        $this->get(route('leaderboard.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('leaderboard.index')
            ->assertSee('Leaderboard');
    }
}
