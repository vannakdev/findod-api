<?php

use Illuminate\Database\Seeder;

class ResidenceTranslationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\RecidencesTranslation::query()->delete();

        $data=[
            [
                "residence_id" =>1,
                "locale" =>"en",
                "res_title"=>"Flat"
            ],
            [
                "residence_id" =>1,
                "locale" =>"km",
                "res_title"=>"ផ្ទះល្វែង"
            ],
            [
                "residence_id" =>1,
                "locale" =>"zh",
                "res_title"=>"平房"
            ],

            [
                "residence_id" =>2,
                "locale" =>"en",
                "res_title"=>"Factory"
            ],
            [
                "residence_id" =>2,
                "locale" =>"km",
                "res_title"=>"រោងចក្រ"
            ],
            [
                "residence_id" =>2,
                "locale" =>"zh",
                "res_title"=>"工厂"
            ],


            [
                "residence_id" =>3,
                "locale" =>"en",
                "res_title"=>"Land"
            ],
            [
                "residence_id" =>3,
                "locale" =>"km",
                "res_title"=>"ដី"
            ],
            [
                "residence_id" =>3,
                "locale" =>"zh",
                "res_title"=>"土地"
            ],

            [
                "residence_id" =>4,
                "locale" =>"en",
                "res_title"=>"Office"
            ],
            [
                "residence_id" =>4,
                "locale" =>"km",
                "res_title"=>"ការិយាល័យ"
            ],
            [
                "residence_id" =>4,
                "locale" =>"zh",
                "res_title"=>"商务"
            ],

            [
                "residence_id" =>5,
                "locale" =>"en",
                "res_title"=>"Shop"
            ],
            [
                "residence_id" =>5,
                "locale" =>"km",
                "res_title"=>"ហាង"
            ],
            [
                "residence_id" =>5,
                "locale" =>"zh",
                "res_title"=>"商店"
            ],

            [
                "residence_id" =>6,
                "locale" =>"en",
                "res_title"=>"Warehouse"
            ],
            [
                "residence_id" =>6,
                "locale" =>"km",
                "res_title"=>"ឃ្លាំង"
            ],
            [
                "residence_id" =>6,
                "locale" =>"zh",
                "res_title"=>"仓库"
            ],

            [
                "residence_id" =>7,
                "locale" =>"en",
                "res_title"=>"Apartment"
            ],
            [
                "residence_id" =>7,
                "locale" =>"km",
                "res_title"=>"អាផាតមិន"
            ],
            [
                "residence_id" =>7,
                "locale" =>"zh",
                "res_title"=>"公寓"
            ],

            [
                "residence_id" =>8,
                "locale" =>"en",
                "res_title"=>"Retail"
            ],
            [
                "residence_id" =>8,
                "locale" =>"km",
                "res_title"=>"ហាងតូចៗ"
            ],
            [
                "residence_id" =>8,
                "locale" =>"zh",
                "res_title"=>"零售店"
            ],


            [
                "residence_id" =>9,
                "locale" =>"en",
                "res_title"=>"Restaurant"
            ],
            [
                "residence_id" =>9,
                "locale" =>"km",
                "res_title"=>""
            ],
            [
                "residence_id" =>9,
                "locale" =>"zh",
                "res_title"=>""
            ],


            [
                "residence_id" =>10,
                "locale" =>"en",
                "res_title"=>"Villa"
            ],
            [
                "residence_id" =>10,
                "locale" =>"km",
                "res_title"=>"វីឡា"
            ],
            [
                "residence_id" =>10,
                "locale" =>"zh",
                "res_title"=>"别墅"
            ],


            [
                "residence_id" =>11,
                "locale" =>"en",
                "res_title"=>"Condo"
            ],
            [
                "residence_id" =>11,
                "locale" =>"km",
                "res_title"=>"ខុនដូ"
            ],
            [
                "residence_id" =>11,
                "locale" =>"zh",
                "res_title"=>"公寓大廈"
            ],



            [
                "residence_id" =>12,
                "locale" =>"en",
                "res_title"=>"Single Family Home"
            ],
            [
                "residence_id" =>12,
                "locale" =>"km",
                "res_title"=>"ផ្ទះដាច់តែឯង"
            ],
            [
                "residence_id" =>12,
                "locale" =>"zh",
                "res_title"=>"独院住宅"
            ],



            [
                "residence_id" =>13,
                "locale" =>"en",
                "res_title"=>"Apartment"
            ],
            [
                "residence_id" =>13,
                "locale" =>"km",
                "res_title"=>"អាផាតមិន"
            ],
            [
                "residence_id" =>13,
                "locale" =>"zh",
                "res_title"=>"公寓"
            ],



            [
                "residence_id" =>14,
                "locale" =>"en",
                "res_title"=>"Flat"
            ],
            [
                "residence_id" =>14,
                "locale" =>"km",
                "res_title"=>"ផ្ទះល្វែង"
            ],
            [
                "residence_id" =>14,
                "locale" =>"zh",
                "res_title"=>"平房"
            ],


            [
                "residence_id" =>15,
                "locale" =>"en",
                "res_title"=>"Hotel"
            ],
            [
                "residence_id" =>15,
                "locale" =>"km",
                "res_title"=>""
            ],
            [
                "residence_id" =>15,
                "locale" =>"zh",
                "res_title"=>""
            ],

            [
                "residence_id" =>16,
                "locale" =>"en",
                "res_title"=>"Borey"
            ],
            [
                "residence_id" =>16,
                "locale" =>"km",
                "res_title"=>""
            ],
            [
                "residence_id" =>16,
                "locale" =>"zh",
                "res_title"=>""
            ],
            [
                "residence_id" =>17,
                "locale" =>"en",
                "res_title"=>"Land"
            ],
            [
                "residence_id" =>17,
                "locale" =>"km",
                "res_title"=>"ដី"
            ],
            [
                "residence_id" =>17,
                "locale" =>"zh",
                "res_title"=>"土地"
            ],

        ];
        foreach($data as $record){
            \App\RecidencesTranslation::create($record);
        }
    }
}
