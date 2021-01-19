<?php

use App\Properties;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            $property = new Properties();
            $property->create([
                // 'pro_title' => 'Home for sale or rent '.$index,
                'pro_price' => $faker->numberBetween($min = 50, $max = 9000),
                'pro_address' => $faker->address(),
                'pro_lng' => $faker->longitude($min = 104.800000, $max = 104.900000),
                'pro_lat' => $faker->latitude($min = 11.500000, $max = 11.580000),
                'pro_residence' => $faker->numberBetween($min = 1, $max = 14),
                'pro_search_type' => $faker->numberBetween($min = 1, $max = 2),
                'pro_floor' => $faker->numberBetween($min = 1, $max = 14),
                'pro_bed_rooms' => $faker->numberBetween($min = 1, $max = 5),
                'pro_bath_rooms' => $faker->numberBetween($min = 1, $max = 5),
                'pro_city' => $faker->city(),
                'pro_thumbnail' => 'thumbnail-'.$faker->numberBetween($min = 1, $max = 20).'.jpg',
                'pro_photos' => json_encode([
                    $faker->numberBetween($min = 1, $max = 20).'.jpg',
                    $faker->numberBetween($min = 1, $max = 20).'.jpg',
                    $faker->numberBetween($min = 1, $max = 20).'.jpg',
                    $faker->numberBetween($min = 1, $max = 20).'.jpg',
                    $faker->numberBetween($min = 1, $max = 20).'.jpg', ]),
                'pro_plan' => json_encode([
                    $faker->numberBetween($min = 1, $max = 20).'.jpg',
                    $faker->numberBetween($min = 1, $max = 20).'.jpg',
                    $faker->numberBetween($min = 1, $max = 20).'.jpg',
                    $faker->numberBetween($min = 1, $max = 20).'.jpg',
                    $faker->numberBetween($min = 1, $max = 20).'.jpg', ]),
            ]);
        }
    }
}
