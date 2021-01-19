<?php

use Illuminate\Database\Seeder;

class FloorCategoryTranslations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\FloorCategoryTranslation::query()->delete();

        $data = [
            [
                'floor_category_id' => 1,
                'locale' => 'en',
                'title' => 'High Floor',
            ],
            [
                'floor_category_id' => 2,
                'locale' => 'en',
                'title' => 'Medium Floor',
            ],
            [
                'floor_category_id' => 3,
                'locale' => 'en',
                'title' => 'Low Floor',
            ],

            [
                'floor_category_id' => 1,
                'locale' => 'km',
                'title' => 'High Floor(kh)',
            ],
            [
                'floor_category_id' => 2,
                'locale' => 'km',
                'title' => 'Medium Floor(kh)',
            ],
            [
                'floor_category_id' => 3,
                'locale' => 'km',
                'title' => 'Low Floor(kh)',
            ],

            [
                'floor_category_id' => 1,
                'locale' => 'zh',
                'title' => 'High Floor(cn)',
            ],

            [
                'floor_category_id' => 2,
                'locale' => 'zh',
                'title' => 'Medium Floor(cn)',
            ],
            [
                'floor_category_id' => 3,
                'locale' => 'zh',
                'title' => 'Low Floor(cn)',
            ],

        ];
        foreach ($data as $record) {
            \App\FloorCategoryTranslation::create($record);
        }
    }
}
