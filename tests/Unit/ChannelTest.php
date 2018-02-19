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
        $channel = create('App\Channel');
        $thread = create('App\Thread', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }

    /** @test */
    public function a_channel_can_be_archived()
    {
        $channel = create('App\Channel');

        $this->assertFalse($channel->archived);

        $channel->archive();

        $this->assertTrue($channel->archived);
    }

    /** @test */
    public function archived_channels_are_excluded_by_default()
    {
        create('App\Channel');
        create('App\Channel', ['archived' => true]);

        $this->assertEquals(1, Channel::count());
    }

    /** @test */
    public function channels_are_sorted_alphabetically_by_default()
    {
        $php = create('App\Channel', ['name' => 'PHP']);
        $basic = create('App\Channel', ['name' => 'Basic']);
        $zsh = create('App\Channel', ['name' => 'Zsh']);

        Channel::all()->assertEquals([$basic, $php, $zsh]);
    }
}
