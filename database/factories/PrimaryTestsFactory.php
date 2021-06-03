<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ticket\PrimaryTests;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(PrimaryTests::class, function (Faker $faker) {
    return [
        'user_id' => auth()->user()->id,
        'name' => $faker->name,
        'receipt_number' => Str::random(10),
        'status' => 1,
    ];
});
