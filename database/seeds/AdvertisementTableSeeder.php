<?php

use Illuminate\Database\Seeder;

class AdvertisementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Advertisement::truncate();
        factory(App\Advertisement::class, 50)->create();
    }
}
