<?php

use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('currency')->truncate();

        DB::table('currency')->insert([
            ['id'=>1, 'title' => 'USD', 'sign' => '$'],
            ['id'=>2,'title' => 'CNY', 'sign' => '¥'],
            ['id'=>3,'title' => 'RIEL', 'sign' => '៛']
        ]);
    }

}
