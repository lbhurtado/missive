<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(get_class(app('missive.sms')), function (Faker $faker) {
    return [
        'from' => $faker->phoneNumber,
        'to' => $faker->phoneNumber,
        'message' => $faker->sentence
    ];
});