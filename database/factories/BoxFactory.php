<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Box;
use Faker\Generator as Faker;

$factory->define(Box::class, function (Faker $faker) {
    return [
        'unit_id' => 1,
        'box_type' => random_int(1,2),
        'status' => 0,
    ];
});
