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

$factory->define(App\Properties::class, function (Faker\Generator $faker) {
    return [
        'pro_title' => 'Home for sale or rent in ' . $faker->city(),
        'pro_price' => $faker->numberBetween($min = 50, $max = 9000),
        'pro_address' => $faker->address(),
        'pro_lng' => $faker->longitude($min = 104.800000, $max = 104.900000),
        'pro_lat' => $faker->latitude($min = 11.500000, $max = 11.580000),
        'pro_residence' => $faker->numberBetween($min = 1, $max = 14),
        'pro_search_type' => $faker->numberBetween($min = 1, $max = 3),
        'pro_floor' => $faker->numberBetween($min = 1, $max = 14),
        'pro_bed_rooms' => $faker->numberBetween($min = 1, $max = 5),
        'pro_bath_rooms' => $faker->numberBetween($min = 1, $max = 5),
        'pro_city' => $faker->city(),
        'pro_photos' => json_encode([
            $faker->numberBetween($min = 1, $max = 20) . '.jpg',
            $faker->numberBetween($min = 1, $max = 20) . '.jpg',
            $faker->numberBetween($min = 1, $max = 20) . '.jpg',
            $faker->numberBetween($min = 1, $max = 20) . '.jpg',
            $faker->numberBetween($min = 1, $max = 20) . '.jpg']),
        'pro_plan' => json_encode([
            $faker->numberBetween($min = 1, $max = 20) . '.jpg',
            $faker->numberBetween($min = 1, $max = 20) . '.jpg',
            $faker->numberBetween($min = 1, $max = 20) . '.jpg',
            $faker->numberBetween($min = 1, $max = 20) . '.jpg',
            $faker->numberBetween($min = 1, $max = 20) . '.jpg'])
    ];
});

$factory->define(App\Advertisement::class, function (Faker\Generator $faker) {
    $startingDate = $faker->dateTimeBetween('this week', '+6 days');
    $endingDate   =$faker->dateTimeBetween($startingDate, strtotime('+15 days'));

    return [
        'title' =>$faker->sentence($nbWords = 10, $variableNbWords = true) ,
        'feature_image' => $faker->numberBetween($min = 1, $max = 11) .'.jpeg',
        'start_date' =>$startingDate,
        'end_date' => $endingDate,
        'user_id' => \App\Users::inRandomOrder()->first()->id,
        'content' => $faker->paragraph($nbSentences = 5, $variableNbSentences = true),
        'thumbnail' => $faker->numberBetween($min = 1, $max = 20) .'.jpeg',
    ];
});
