<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

$factory->define(get_class(app('missive.contact')), function (Faker $faker) {
    $faker = FakerFactory::create('en_PH');
    
    return [
        'mobile' => $faker->mobileNumber,
        'handle' => $faker->name,
    ];
});
