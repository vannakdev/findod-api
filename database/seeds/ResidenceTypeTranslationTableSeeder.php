<?php

use Illuminate\Database\Seeder;

class ResidenceTypeTranslationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ResidenceTypeTranslation::query()->delete();

        $data=[
            [
                "residence_type_id" =>1,
                "locale" =>"en",
                "title"=>"Commercial property"
            ],
            [
                "residence_type_id" =>2,
                "locale" =>"en",
                "title"=>"Residential Property"
            ],
            [
                "residence_type_id" =>3,
                "locale" =>"en",
                "title"=>"Vacant Land"
            ],


            [
                "residence_type_id" =>1,
                "locale" =>"km",
                "title"=>"ទ្រព្យសម្បត្តិពាណិជ្ជកម្ម"
            ],
            [
                "residence_type_id" =>2,
                "locale" =>"km",
                "title"=>"លំនៅដ្ឋាន"
            ],
            [
                "residence_type_id" =>3,
                "locale" =>"km",
                "title"=>"ដីទំនេរ"
            ],


            [
                "residence_type_id" =>1,
                "locale" =>"zh",
                "title"=>"商业地产"
            ],
            [
                "residence_type_id" =>2,
                "locale" =>"zh",
                "title"=>"住宅物业"
            ],
            [
                "residence_type_id" =>3,
                "locale" =>"zh",
                "title"=>"空地;闲置地"
            ],
        ];
        foreach($data as $record){
            \App\ResidenceTypeTranslation::create($record);
        }
    }
}
