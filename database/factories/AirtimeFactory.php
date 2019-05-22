<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(get_class(app('missive.airtime')), function (Faker $faker) {
    return [
        'key' => $faker->word,
        'credits' => $faker->numberBetween(1,100),
    ];
});