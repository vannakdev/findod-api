<?php

use Illuminate\Database\Seeder;

class StatisticOfPirceRangesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('statistic_of_price_ranges')->truncate();

        $data=[
            [
                "min_price" =>0,
                "max_price" => 50,
                "increasing_number"=>50
            ],
            [
                "min_price" =>50,
                "max_price" => 100,
                "increasing_number"=>100
            ],
            [
                "min_price" =>100,
                "max_price" => 200,
                "increasing_number"=>100
            ],
            [
                "min_price" =>200,
                "max_price" => 300,
                "increasing_number"=>100
            ],
            [
                "min_price" =>300,
                "max_price" => 400,
                "increasing_number"=>100
            ],
            [
                "min_price" =>400,
                "max_price" => 500,
                "increasing_number"=>100
            ],
            [
                "min_price" =>500,
                "max_price" => 1000,
                "increasing_number"=>1000
            ],
            [
                "min_price" =>1000,
                "max_price" => 2000,
                "increasing_number"=>1000
            ],
            [
                "min_price" =>2000,
                "max_price" => 3000,
                "increasing_number"=>1000
            ],
            [
                "min_price" =>3000,
                "max_price" =>4000,
                "increasing_number"=>1000
            ],
            [
                "min_price" =>4000,
                "max_price" => 5000,
                "increasing_number"=>5000
            ],            
            [
                "min_price" =>5000,
                "max_price" => 10000,
                "increasing_number"=>10000
            ]
           
        ];
        foreach($data as $record){
            \App\StatisticOfPriceRanges::create($record);
        }
    

    }

}
