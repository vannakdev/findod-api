<?php

use Illuminate\Database\Seeder;

class PropertyStatusTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('property_status_translations')->truncate();

        $data=[
            [
                "property_status_id" =>1,
                "locale" =>"en",
                "title"=>"New property"
            ],
            [
                "property_status_id" =>1,
                "locale" =>"km",
                "title"=>"ទ្រព្យសម្បត្តិថ្មី"
            ],
            [
                "property_status_id" =>1,
                "locale" =>"zh",
                "title"=>"新房产"
            ],
            [
                "property_status_id" =>2,
                "locale" =>"en",
                "title"=>"Resale property"
            ],
            [
                "property_status_id" =>2,
                "locale" =>"km",
                "title"=>"ទ្រព្យសម្បត្តិលក់បន្ត"
            ],
            [
                "property_status_id" =>2,
                "locale" =>"zh",
                "title"=>"财产转让"
            ],
            [
                "property_status_id" =>3,
                "locale" =>"en",
                "title"=>"New project"
            ],
            [
                "property_status_id" =>3,
                "locale" =>"km",
                "title"=>"គម្រោង​ថ្មី"
            ],
            [
                "property_status_id" =>3,
                "locale" =>"zh",
                "title"=>"新项目"
            ],
        ];
        foreach($data as $record){
            \App\PropertyStatusTranslation::create($record);
        }
    }
}
