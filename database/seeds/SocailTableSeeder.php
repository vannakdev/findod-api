<?php

use Illuminate\Database\Seeder;

class SocailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('social')->truncate();
        DB::table('social')->insert([
            ['id'=>1,'provider' => 'Facebook'],
            ['id'=>2,'provider' => 'Google'],
            ['id'=>3,'provider' => 'Find OD APP']
        ]);
    }
}
