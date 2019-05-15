<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(get_class(app('missive.contact')), function (Faker $faker) {
    return [
        'mobile' => $faker->phoneNumber,
        'handle' => $faker->name,
    ];
});
