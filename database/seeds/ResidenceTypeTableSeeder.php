<?php

use Illuminate\Database\Seeder;

class ResidenceTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ResidenceType::query()->delete();
        $data = [
            [
                'id' =>1,
                'status' =>1,
            ],
            [
                'id' =>2,
                'status' =>1,
            ],
            [
                'id' =>3,
                'status' =>1,
            ],
        ];
        foreach ($data as $record) {
            \App\ResidenceType::create($record);
        }
    }
}
