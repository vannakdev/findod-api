<?php

use Illuminate\Database\Seeder;

class AmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Amenities::query()->delete();

        DB::table('amenities')->insert([
            [
                'id' => 1,
                'title' => 'Air Conditioning',
                'icon' => 'air-conditioner.png', ],
            [
                'id' => 5,
                'title' => 'Lift',
                'icon' => 'elevator.png', ],
            [
                'id' => 6,
                'title' => 'Parking',
                'icon' => 'parking.png', ],
            [
                'id' => 7,
                'title' => 'Separate Shower',
                'icon' => 'shower.png', ],
            [
                'id' => 17,
                'title' => 'Power Backup',
                'icon' => 'shutdown.png', ],
            [
                'id' => 18,
                'title' => 'Security',
                'icon' => 'protect.png', ],
            [
                'id' => 19,
                'title' => 'Cycling & jogging track',
                'icon' => 'cysling.png', ],
            [
                'id' => 20,
                'title' => 'Gated Community',
                'icon' => 'user-groups.png', ],
            [
                'id' => 21,
                'title' => 'Swimming Pool',
                'icon' => 'swimming.png', ],
            [
                'id' => 22,
                'title' => 'Gym/Fitness center',
                'icon' => 'exercise.png', ],
            [
                'id' => 23,
                'title' => 'Furnished',
                'icon' => 'armchair.png', ],
            [
                'id' => 24,
                'title' => 'Balcony',
                'icon' => 'balcony.png', ],
            [
                'id' => 25,
                'title' => 'Non-Flooding',
                'icon' => 'floods.png', ],
            [
                'id' => 26,
                'title' => 'On main road',
                'icon' => 'road.png', ],
            [
                'id' => 27,
                'title' => 'Pay TV',
                'icon' => 'tv.png', ],
            [
                'id' => 28,
                'title' => 'Pet Friendly',
                'icon' => 'dog-training.png', ],
            [
                'id' => 29,
                'title' => 'Jacuzzi',
                'icon' => 'jacuzzi.png', ],
            [
                'id' => 30,
                'title' => 'Sauna',
                'icon' => 'sauna.png', ],
            [
                'id' => 31,
                'title' => 'Tennis Court',
                'icon' => 'tennis-racquet.png', ],
            [
                'id' => 32,
                'title' => 'Alarm System',
                'icon' => 'home-alarm.png', ],
            [
                'id' => 33,
                'title' => 'Video Security',
                'icon' => 'bullet-camera.png', ],
            [
                'id' => 34,
                'title' => 'Reception 24/7',
                'icon' => 'front-desk.png', ],
            [
                'id' => 35,
                'title' => 'Fire sprinkler system',
                'icon' => 'fires.png', ],
            [
                'id' => 36,
                'title' => 'Ocean Views',
                'icon' => 'beach.png', ],
            [
                'id' => 37,
                'title' => 'City Views',
                'icon' => 'city.png', ],
            [
                'id' => 38,
                'title' => 'Other',
                'icon' => 'other.png', ],
            [
                'id' => 39,
                'title' => 'Bash Room',
                'icon' => 'bashroom.png', ],
            [
                'id' => 40,
                'title' => 'Bed Room',
                'icon' => 'bedroom.png', ],
            [
                'id' => 41,
                'title' => 'Floor',
                'icon' => 'floor.png', ],
        ]);
    }
}
