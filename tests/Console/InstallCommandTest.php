<?php

namespace Tests\Console;

use Illuminate\Support\Facades\File;
use Mockery;
use Tests\TestCase;

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
            $mock->shouldReceive('confirm')->once()->andReturn(true);
            $mock->shouldReceive('call')->with('key:generate');
            $mock->shouldReceive('call')->with('migrate')->once();
        });

        $this->artisan('council:install', ['--no-interaction' => true]);
    }

    /** @test */
    function it_sets_the_database_env_config()
    {
        $this->partialMock(['ask', 'secret'], function ($mock) {
            $mock->shouldReceive('ask')->with('Database name')->andReturn('mydatabase');
            $mock->shouldReceive('ask')->with('Database user')->andReturn('johndoe');
            $mock->shouldReceive('secret')->with('Database password ("null" for no password)')->andReturn('password');
        });

        $this->artisan('council:install', ['--no-interaction' => true]);

        $this->assertEnvKeyEquals('DB_DATABASE', 'mydatabase');
        $this->assertEnvKeyEquals('DB_USERNAME', 'johndoe');
        $this->assertEnvKeyEquals('DB_PASSWORD', 'password');
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
