<?php

namespace Tests\Feature;

use App\Channel;
use Tests\TestCase;
use PHPUnit\Framework\Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        EloquentCollection::macro('assertEquals', function ($items) {
            Assert::assertEquals(count($this), count($items));

            $this->zip($items)->each(function ($pair) {
                [$actual, $expected] = $pair;
                
                Assert::assertTrue($actual->is($expected));
            });
        });
    }

    /** @test */
    public function a_channel_consists_of_threads()
    {
        $channel = create(\App\Channel::class);
        $thread = create(\App\Thread::class, ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }

    /** @test */
    public function a_channel_can_be_archived()
    {
        $channel = create(\App\Channel::class);

        $this->assertFalse($channel->archived);

        $channel->archive();

        $this->assertTrue($channel->archived);
    }

    /** @test */
    public function archived_channels_are_excluded_by_default()
    {
        create(\App\Channel::class);
        create(\App\Channel::class, ['archived' => true]);

        $this->assertEquals(1, Channel::count());
    }

    /** @test */
    public function channels_are_sorted_alphabetically_by_default()
    {
        $php = create(\App\Channel::class, ['name' => 'PHP']);
        $basic = create(\App\Channel::class, ['name' => 'Basic']);
        $zsh = create(\App\Channel::class, ['name' => 'Zsh']);

        Channel::all()->assertEquals([$basic, $php, $zsh]);
    }
}
