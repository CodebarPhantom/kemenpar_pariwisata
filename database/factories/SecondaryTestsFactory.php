<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ticket\SecondaryTests;
use Faker\Generator as Faker;

$factory->define(SecondaryTests::class, function (Faker $faker) {
    return [
        'quantity' => $faker->biasedNumberBetween(1, 10),
    ];
});
