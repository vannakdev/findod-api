<?php

use Illuminate\Database\Seeder;

class AgeCategoryTranslationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('age_category_translations')->truncate();

        $data = [
            [
                'age_category_id' =>1,
                'locale' =>'en',
                'title'=>'Less than 1 Year',
            ],
            [
                'age_category_id' =>2,
                'locale' =>'en',
                'title'=>'Less than 2 Years',
            ],
            [
                'age_category_id' =>3,
                'locale' =>'en',
                'title'=>'Less than 5 Years',
            ],
            [
                'age_category_id' =>4,
                'locale' =>'en',
                'title'=>'Less than 10 Years',
            ],
            [
                'age_category_id' =>5,
                'locale' =>'en',
                'title'=>'More than 10 Years',
            ],

            [
                'age_category_id' =>1,
                'locale' =>'km',
                'title'=>'តិចជាង 1 ឆ្នាំ',
            ],
            [
                'age_category_id' =>2,
                'locale' =>'km',
                'title'=>'តិចជាង 2 ឆ្នាំ',
            ],
            [
                'age_category_id' =>3,
                'locale' =>'km',
                'title'=>'តិចជាង 5 ឆ្នាំ',
            ],
            [
                'age_category_id' =>4,
                'locale' =>'km',
                'title'=>'តិចជាង 10 ឆ្នាំ',
            ],
            [
                'age_category_id' =>5,
                'locale' =>'km',
                'title'=>'ច្រើនជាង 10 ឆ្នាំ',
            ],

            [
                'age_category_id' =>1,
                'locale' =>'zh',
                'title'=>'不到 1年',
            ],
            [
                'age_category_id' =>2,
                'locale' =>'zh',
                'title'=>'不到 2年',
            ],
            [
                'age_category_id' =>3,
                'locale' =>'zh',
                'title'=>'不到 5年',
            ],
            [
                'age_category_id' =>4,
                'locale' =>'zh',
                'title'=>'不到 10年',
            ],
            [
                'age_category_id' =>5,
                'locale' =>'zh',
                'title'=>'超过 10年',
            ],

        ];
        foreach ($data as $record) {
            \App\AgeCategoryTranslation::create($record);
        }
    }
}
