<?php

use Illuminate\Database\Seeder;

class AmenityTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\AmenitiesTranslation::query()->delete();

        $data = [
            ['amenity_id' => 1, 'locale' => 'en', 'title' => 'Lift/Lights'],
            ['amenity_id' => 2, 'locale' => 'en', 'title' => 'Power Backup'],
            ['amenity_id' => 3, 'locale' => 'en', 'title' => 'Security'],
            ['amenity_id' => 4, 'locale' => 'en', 'title' => 'Cycling & jogging track'],
            ['amenity_id' => 5, 'locale' => 'en', 'title' => 'Gated Community/Townhouse community'],
            ['amenity_id' => 6, 'locale' => 'en', 'title' => 'Swimming Pool'],
            ['amenity_id' => 7, 'locale' => 'en', 'title' => 'Gym/Fitness center'],
            ['amenity_id' => 8, 'locale' => 'en', 'title' => 'Furnished'],
            ['amenity_id' => 9, 'locale' => 'en', 'title' => 'Balcony'],
            ['amenity_id' => 10, 'locale' => 'en', 'title' => 'Non-Flooding'],
            ['amenity_id' => 11, 'locale' => 'en', 'title' => 'On main road'],
            ['amenity_id' => 12, 'locale' => 'en', 'title' => 'Pay TV/cable TV'],
            ['amenity_id' => 13, 'locale' => 'en', 'title' => 'Pet Friendly'],
            ['amenity_id' => 14, 'locale' => 'en', 'title' => 'Jacuzzi spa'],
            ['amenity_id' => 15, 'locale' => 'en', 'title' => 'Sauna/Steam'],
            ['amenity_id' => 16, 'locale' => 'en', 'title' => 'Tennis Court'],
            ['amenity_id' => 17, 'locale' => 'en', 'title' => 'Alarm System'],
            ['amenity_id' => 18, 'locale' => 'en', 'title' => 'Video Security'],
            ['amenity_id' => 19, 'locale' => 'en', 'title' => 'Reception 24/7'],
            ['amenity_id' => 20, 'locale' => 'en', 'title' => 'Fire sprinkler system'],
            ['amenity_id' => 21, 'locale' => 'en', 'title' => 'Ocean Views'],
            ['amenity_id' => 22, 'locale' => 'en', 'title' => 'City Views'],
            ['amenity_id' => 23, 'locale' => 'en', 'title' => 'Other'],
            ['amenity_id' => 24, 'locale' => 'en', 'title' => 'Bash Room/Bathroom'],
            ['amenity_id' => 25, 'locale' => 'en', 'title' => 'Bed Room'],
            ['amenity_id' => 26, 'locale' => 'en', 'title' => 'Floor'],

            ['amenity_id' => 1, 'locale' => 'km', 'title' => 'អំពូភ្លើង'],
            ['amenity_id' => 2, 'locale' => 'km', 'title' => 'អំពូលបំរុង'],
            ['amenity_id' => 3, 'locale' => 'km', 'title' => 'សន្តិសុខ'],
            ['amenity_id' => 4, 'locale' => 'km', 'title' => 'ជិះកង់ និង រត់ហាត់ប្រាណ'],
            ['amenity_id' => 5, 'locale' => 'km', 'title' => 'ផ្ទះដែលមានរបងខណ្ឌរៀងខ្លួន/សហគមន៍ផ្ទះល្វែង'],
            ['amenity_id' => 6, 'locale' => 'km', 'title' => 'អាងហែលទឹក'],
            ['amenity_id' => 7, 'locale' => 'km', 'title' => 'លំហាត់ប្រាណ/ទីកន្លែងហាត់ប្រាណ'],
            ['amenity_id' => 8, 'locale' => 'km', 'title' => 'គ្រឿងសង្ហារឹម'],
            ['amenity_id' => 9, 'locale' => 'km', 'title' => 'រានខាងមុខ'],
            ['amenity_id' => 10, 'locale' => 'km', 'title' => 'មិនមានការជនលិច'],
            ['amenity_id' => 11, 'locale' => 'km', 'title' => 'លើផ្លូវធំ'],
            ['amenity_id' => 12, 'locale' => 'km', 'title' => 'បង់ថ្លៃទូរទស្សន៏'],
            ['amenity_id' => 13, 'locale' => 'km', 'title' => 'កន្លែងដាក់សត្វចញ្ចឹម'],
            ['amenity_id' => 14, 'locale' => 'km', 'title' => 'ស្ប៉ា'],
            ['amenity_id' => 15, 'locale' => 'km', 'title' => 'ស្ទីម/ សូណា'],
            ['amenity_id' => 16, 'locale' => 'km', 'title' => 'ទីលាន​វាយ​តេន​នី​ស'],
            ['amenity_id' => 17, 'locale' => 'km', 'title' => 'ប្រព័ន្ធសំឡេងរោទិ៍'],
            ['amenity_id' => 18, 'locale' => 'km', 'title' => 'វីដេអូសុវត្ថិភាព'],
            ['amenity_id' => 19, 'locale' => 'km', 'title' => 'អ្នកទទួលភ្ញៀវ 24/7'],
            ['amenity_id' => 20, 'locale' => 'km', 'title' => 'ប្រព័ន្ធពន្លត់អគ្គីភ័យ'],
            ['amenity_id' => 21, 'locale' => 'km', 'title' => 'ទិដ្ឋភាពសមុទ្រ'],
            ['amenity_id' => 22, 'locale' => 'km', 'title' => 'ទិដ្ឋភាពទីក្រុង'],
            ['amenity_id' => 23, 'locale' => 'km', 'title' => 'ផ្សេងទៀត'],
            ['amenity_id' => 24, 'locale' => 'km', 'title' => 'បន្ទប់ទឹក'],
            ['amenity_id' => 25, 'locale' => 'km', 'title' => 'បន្ទប់គេង'],
            ['amenity_id' => 26, 'locale' => 'km', 'title' => 'ជាន់'],

            ['amenity_id' => 1, 'locale' => 'zh', 'title' =>'灯火'],
            ['amenity_id' => 2, 'locale' => 'zh', 'title' =>'电源备份'],
            ['amenity_id' => 3, 'locale' => 'zh', 'title' =>'保安/安全'],
            ['amenity_id' => 4, 'locale' => 'zh', 'title' =>'骑自行车和慢跑'],
            ['amenity_id' => 5, 'locale' => 'zh', 'title' =>'联排别墅小区'],
            ['amenity_id' => 6, 'locale' => 'zh', 'title' =>'游泳池'],
            ['amenity_id' => 7, 'locale' => 'zh', 'title' =>'健身房'],
            ['amenity_id' => 8, 'locale' => 'zh', 'title' =>'家具'],
            ['amenity_id' => 9, 'locale' => 'zh', 'title' =>'阳台'],
            ['amenity_id' => 10, 'locale' => 'zh', 'title' =>'非淹水'],
            ['amenity_id' => 11, 'locale' => 'zh', 'title' =>'大路/大街'],
            ['amenity_id' => 12, 'locale' => 'zh', 'title' =>'电缆电视'],
            ['amenity_id' => 13, 'locale' => 'zh', 'title' =>'动物受欢迎的'],
            ['amenity_id' => 14, 'locale' => 'zh', 'title' =>'按摩浴缸'],
            ['amenity_id' => 15, 'locale' => 'zh', 'title' =>'桑拿浴'],
            ['amenity_id' => 16, 'locale' => 'zh', 'title' =>'网球场'],
            ['amenity_id' => 17, 'locale' => 'zh', 'title' =>'报警系统'],
            ['amenity_id' => 18, 'locale' => 'zh', 'title' =>'视频安防'],
            ['amenity_id' => 19, 'locale' => 'zh', 'title' =>'前台/ 接待24/7'],
            ['amenity_id' => 20, 'locale' => 'zh', 'title' =>'自动喷水灭火系统'],
            ['amenity_id' => 21, 'locale' => 'zh', 'title' =>'海洋景观'],
            ['amenity_id' => 22, 'locale' => 'zh', 'title' =>'城市风景'],
            ['amenity_id' => 23, 'locale' => 'zh', 'title' =>'其他'],
            ['amenity_id' => 24, 'locale' => 'zh', 'title' =>'卫生间'],
            ['amenity_id' => 25, 'locale' => 'zh', 'title' =>'卧室'],
            ['amenity_id' => 26, 'locale' => 'zh', 'title' =>'楼'],

        ];
        foreach ($data as $record) {
            \App\AmenitiesTranslation::create($record);
        }
    }
}
