<?php

use Illuminate\Database\Seeder;

class PropertyTypeTranslationsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\PropertyTypeTranslation::query()->delete();

        $data = [
            [
                "property_type_id" => 1,
                "locale" => "en",
                "title" => "Sale",
            ],
            [
                "property_type_id" => 2,
                "locale" => "en",
                "title" => "Rent",
            ],

            [
                "property_type_id" => 1,
                "locale" => "km",
                "title" => "លក់",
            ],
            [
                "property_type_id" => 2,
                "locale" => "km",
                "title" => "ជួល",
            ],
           

            [
                "property_type_id" => 1,
                "locale" => "zh",
                "title" => "出售",
            ],

            [
                "property_type_id" => 2,
                "locale" => "zh",
                "title" => "出租",
            ],
            

        ];
        foreach ($data as $record) {
            \App\PropertyTypeTranslation::create($record);
        }
    }

}
