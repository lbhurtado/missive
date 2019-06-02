<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

$factory->define(get_class(app('missive.sms')), function (Faker $faker) {
    $faker = FakerFactory::create('en_PH');
    
    return [
        'from' => $faker->mobileNumber,
        'to' => $faker->mobileNumber,
        'message' => $faker->sentence
    ];
});