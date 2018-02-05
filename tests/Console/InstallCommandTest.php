<?php

namespace Tests\Console;

use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\File;

class InstallCommandTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        File::move('.env', '.env.backup');

        config(['app.key' => '']);
    }

    public function tearDown()
    {
        parent::tearDown();

        File::move('.env.backup', '.env');
    }

    /** @test */
    function it_creates_the_example_file()
    {
        $this->assertFileNotExists('.env');

        $this->artisan('council:install', ['--no-interaction' => true]);

        $this->assertFileExists('.env');
    }

    /** @test */
    function it_generates_an_app_key()
    {
        $key = 'APP_KEY';

        $this->artisan('council:install', ['--no-interaction' => true]);

        $this->assertStringStartsWith('base64:', $this->getEnvValue($key));
    }

    /** @test */
    function it_optionally_migrates_the_database()
    {
        $this->partialMock(['confirm', 'call'], function ($mock) {
            $mock->shouldReceive('confirm')->andReturn(true);
            $mock->shouldReceive('call')->with('key:generate');
            $mock->shouldReceive('call')->with('migrate')->once();
            $mock->shouldReceive('call')->with('cache:clear')->once();
        });

        $this->artisan('council:install', ['--no-interaction' => true]);
    }

    /** @test */
    function it_sets_the_database_env_config()
    {
        $this->partialMock(['ask', 'askHiddenWithDefault'], function ($mock) {
            $mock->shouldReceive('ask')->with('Database name')->andReturn('mydatabase');
            $mock->shouldReceive('ask')->with('Database port', 3306)->andReturn(3306);
            $mock->shouldReceive('ask')->with('Database user')->andReturn('johndoe');
            $mock->shouldReceive('askHiddenWithDefault')->with('Database password (leave blank for no password)')->andReturn('password');
        });

        $this->artisan('council:install', ['--no-interaction' => true]);

        $this->assertEnvKeyEquals('DB_DATABASE', 'mydatabase');
        $this->assertEnvKeyEquals('DB_PORT', '3306');
        $this->assertEnvKeyEquals('DB_USERNAME', 'johndoe');
        $this->assertEnvKeyEquals('DB_PASSWORD', 'password');
    }

    /** @test */
    function it_sets_the_pusher_env_config_if_you_do_want_to_broadcast_with_pusher()
    {
        $this->partialMock(['ask', 'confirm', 'secret'], function ($mock) {
            $mock->shouldReceive('ask')->with('Database name')->andReturn('mydatabase');
            $mock->shouldReceive('ask')->with('Database port', 3306)->andReturn(3306);
            $mock->shouldReceive('ask')->with('Database user')->andReturn('johndoe');
            $mock->shouldReceive('secret')->with('Database password ("null" for no password)')->andReturn('password');

            $mock->shouldReceive('confirm')->andReturn(true);
            $mock->shouldReceive('ask')->with('Pusher App ID')->andReturn(123456);
            $mock->shouldReceive('ask')->with('Pusher Key')->andReturn('my1pusher2key3');
            $mock->shouldReceive('ask')->with('Pusher Secret Key')->andReturn('my1pusher2secret3key');
            $mock->shouldReceive('ask')->with('Pusher Cluster')->andReturn('pushercluster');
        });

        $this->artisan('council:install', ['--no-interaction' => true]);

        $this->assertEnvKeyEquals('BROADCAST_DRIVER', 'pusher');
        $this->assertEnvKeyEquals('PUSHER_APP_ID', '123456');
        $this->assertEnvKeyEquals('MIX_PUSHER_APP_KEY', 'my1pusher2key3');
        $this->assertEnvKeyEquals('PUSHER_APP_SECRET', 'my1pusher2secret3key');
        $this->assertEnvKeyEquals('MIX_PUSHER_APP_CLUSTER', 'pushercluster');
    }

    /** @test */
    function it_does_not_set_the_pusher_env_config_if_you_do_not_want_to_broadcast_with_pusher()
    {
        $this->partialMock(['ask', 'confirm', 'secret'], function ($mock) {
            $mock->shouldReceive('ask')->with('Database name')->andReturn('mydatabase');
            $mock->shouldReceive('ask')->with('Database port', 3306)->andReturn(3306);
            $mock->shouldReceive('ask')->with('Database user')->andReturn('johndoe');
            $mock->shouldReceive('secret')->with('Database password ("null" for no password)')->andReturn('password');

            $mock->shouldReceive('confirm')->once()->andReturn(true); //Asks if you want to run migrations

            $mock->shouldReceive('confirm')->once()->andReturn(false); //Asks if you want to broadcast with Pusher
        });

        $this->artisan('council:install', ['--no-interaction' => true]);

        $this->assertEnvKeyEquals('BROADCAST_DRIVER', 'null');
    }

    protected function partialMock($methods, $assertions = null)
    {
        $assertions = $assertions ?? function () {};

        $methods = implode(',', (array) $methods);

        $command = Mockery::mock("App\Console\Commands\InstallCommand[{$methods}]", $assertions);

        app('Illuminate\Contracts\Console\Kernel')->registerCommand($command);

        return $command;
    }

    protected function assertEnvKeyEquals($key, $value)
    {
        $this->assertEquals($value, $this->getEnvValue($key));
    }

    protected function getEnvValue($key)
    {
        $file = file_get_contents('.env');

        preg_match("/{$key}=(.*)/", $file, $matches);

        return $matches[1];
    }
}
