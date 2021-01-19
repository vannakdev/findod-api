<?php

use Illuminate\Database\Seeder;

class AgeCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\AgeCategory::query()->delete();
        $data=[
            [
                "id" =>1,
                "status" =>1,
                "sign"=>'< 1',
            ],
            [
                "id" =>2,
                "status" =>1,
                "sign"=>'< 2',
            ],
            [
                "id" =>3,
                "status" =>1,
                "sign"=>'< 5',
            ],
            [
                "id" =>4,
                "status" =>1,
                "sign"=>'< 10',
            ],
            [
                "id" =>5,
                "status" =>1,
                "sign"=>'10 +'
            ]
        ];
        foreach($data as $record){
            \App\AgeCategory::create($record);
        }
    }
}
