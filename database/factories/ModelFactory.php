<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'confirmed' => true
    ];
});

$factory->state(App\User::class, 'unconfirmed', function () {
    return [
        'confirmed' => false
    ];
});

$factory->define(App\Thread::class, function ($faker) {
    $title = $faker->sentence;

    return [
        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },
        'channel_id' => function () {
            return factory(\App\Channel::class)->create()->id;
        },
        'title' => $title,
        'body'  => $faker->paragraph,
        'visits' => 0,
        'slug' => str_slug($title),
        'locked' => false
    ];
});

$factory->state(App\Thread::class, 'from_existing_channels_and_users', function ($faker) {
    $title = $faker->sentence;

    return [
        'user_id' => function () {
            return \App\User::all()->random()->id;
        },
        'channel_id' => function () {
            return \App\Channel::all()->random()->id;
        },
        'title' => $title,
        'body'  => $faker->paragraph,
        'visits' => $faker->numberBetween(0, 35),
        'slug' => str_slug($title),
        'locked' => $faker->boolean(15)
    ];
});

$factory->define(App\Channel::class, function ($faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->sentence,
        'archived' => false,
        'color' => $faker->hexcolor
    ];
});

$factory->define(App\Reply::class, function ($faker) {
    return [
        'thread_id' => function () {
            return factory(\App\Thread::class)->create()->id;
        },
        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },
        'body'  => $faker->paragraph
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function ($faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => \App\Notifications\ThreadWasUpdated::class,
        'notifiable_id' => function () {
            return auth()->id() ?: factory(\App\User::class)->create()->id;
        },
        'notifiable_type' => \App\User::class,
        'data' => ['foo' => 'bar']
    ];
});
