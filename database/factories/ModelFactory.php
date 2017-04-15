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
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;
    $photo = $faker->image(storage_path('app\public\photos'), 500, 500, null, false);
    return [
        'nickname' => $faker->unique()->firstName,
        'email' => $faker->unique()->safeEmail,
        'photo' => 'photos/'.$photo,
        'photo_mini' => 'photos/'.$photo,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Post::class, function (Faker\Generator $faker) {
    $date = $faker->date();
    return [
        'text' => $faker->realText(),
        'author_id' => 1,
        'tags' => $faker->words(10),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Subscription::class, function (Faker\Generator $faker) {
    return [
        'user_id' => 1,
        'target_id' => 2,
    ];
});