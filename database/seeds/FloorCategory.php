<?php

use Illuminate\Database\Seeder;

class FloorCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\FloorCategory::query()->delete();
        $data = [
            [
                'id' =>1,
                'project_name_count' =>1,
                'rule'=> 'G-11',
            ],
            [
                'id' =>2,
                'project_name_count' =>1,
                'rule'=> '12-23',
            ],
            [
                'id' =>3,
                'project_name_count' =>1,
                'rule'=> '24-33',
            ],
        ];
        foreach ($data as $record) {
            \App\FloorCategory::create($record);
        }
    }
}
