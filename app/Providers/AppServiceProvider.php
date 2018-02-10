<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Trending;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Trending $trending)
    {
        \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');

        view()->share('channels', \App\Channel::all());
        view()->share('trending', $trending->get());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
