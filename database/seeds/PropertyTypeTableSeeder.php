<?php

use Illuminate\Database\Seeder;

class PropertyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\PropertyType::query()->delete();
        DB::table('property_type')->insert([
            ['id'=>1, 'title' => 'Sale', 'status' => 1],
            ['id'=>2, 'title' => 'Rent', 'status' => 1],
        ]);
    }
}
