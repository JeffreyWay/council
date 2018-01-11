<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'council:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simplify installation process';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->welcome();

        $this->createEnvFile();

        if (strlen(config('app.key')) === 0) {
            $this->call('key:generate');

            $this->line("~ Secret key properly generated.");
        }

        $this->updateEnvironmentFile($this->requestDatabaseCredentials());

        if ($this->confirm('Do you want to migrate the database?', false)) {
            $this->call('migrate');

            $this->line("~ Database successfully migrated.");
        }

        $this->goodbye();
    }

    /**
     * Update the .env file from an array of $key => $value pairs.
     *
     * @param  array $updatedValues
     * @return void
     */
    protected function updateEnvironmentFile($updatedValues)
    {
        $envFile = $this->laravel->environmentFilePath();

        foreach ($updatedValues as $key => $value) {
            file_put_contents($envFile, preg_replace(
                "/{$key}=(.*)/",
                "{$key}={$value}",
                file_get_contents($envFile)
            ));
        }
    }

    /**
     * Display the welcome message.
     */
    protected function welcome()
    {
        $this->info(">> Welcome to the Council installation process! <<");
    }

    /**
     * Display the completion message.
     */
    protected function goodbye()
    {
        $this->info(">> The installation process is complete. Enjoy your new forum! <<");
    }

    /**
     * Request the local database details from the user.
     *
     * @return array
     */
    protected function requestDatabaseCredentials()
    {
        return [
            'DB_DATABASE' => $this->ask('Database name'),
            'DB_USERNAME' => $this->ask('Database user'),
            'DB_PASSWORD' => $this->secret('Database password ("null" for no password)'),
        ];
    }

    /**
     * Create the initial .env file.
     */
    protected function createEnvFile()
    {
        if (! file_exists('.env')) {
            exec('cp .env.example .env');

            $this->line(".env file successfully created");
        }
    }
}
