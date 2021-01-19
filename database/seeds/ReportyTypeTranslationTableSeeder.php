<?php

use Illuminate\Database\Seeder;

class ReportyTypeTranslationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\TypeOfPropertyReportTranslation::query()->delete();

        $data=[
            [
                "report_type_id" =>1,
                "locale" =>"en",
                "title"=>"Any...",
                "content"=>""
            ],
            [
                "report_type_id" =>2,
                "locale" =>"en",
                "title"=>"Property already sold",
                "content"=>"Buyer found the property on the site, after contact with the seller they confirm that the property ready sold."
            ],
            [
                "report_type_id" =>3,
                "locale" =>"en",
                "title"=>"Ads is duplicate",
                "content"=>"Buyers expect a response in what they consider reasonable time. Is it reasonable of your buyers to expect a response inside of 24 hours to at least say you will get back to them?"
            ],

            [
                "report_type_id" =>4,
                "locale" =>"en",
                "title"=>"Fraud reason",
                "content"=>"Fraud reason is concerned with theory and practice of fraudulently representing online advertisement impressions, clicks, conversion or data events in order to generate revenue. While ad fraud is more generally associated with banner ads, video ads and in"
            ],

            
            [
                "report_type_id" =>1,
                "locale" =>"km",
                "title"=>"ផ្សេងៗទៀត...",
                "content"=>""
            ],

            [
                "report_type_id" =>2,
                "locale" =>"km",
                "title"=>"អចលនទ្រព្យបានលក់រួចហើយ",
                "content"=>"អ្នកទិញបានរកឃើញទ្រព្យសម្បត្តិនៅលើគេហទំព័របន្ទាប់ពីទំនាក់ទំនងជាមួយអ្នកលក់ពួកគេបានបញ្ជាក់ថាទ្រព្យសម្បត្តិបានលក់រួចហើយ។"
            ],
            [
                "report_type_id" =>3,
                "locale" =>"km",
                "title"=>"ពាណិជ្ជកម្មស្ទួន",
                "content"=>"ស្ទួនពាណិជ្ជកម្មគឺជាការផ្សាយពាណិជ្ជកម្មពីរឬច្រើនដែលមានធាតុ / សេវាកម្មស្រដៀងគ្នាចំណងជើងផ្សព្វផ្សាយសេចក្ដីពណ៌នានិង / ឬរូបថតដែលបានបង្ហោះដោយអ្នកប្រើដូចគ្នា។"
            ],
            [
                "report_type_id" =>4,
                "locale" =>"km",
                "title"=>"ហេតុផលក្លែងបន្លំ",
                "content"=>"ហេតុផលក្លែងបន្លំពាក់ព័ន្ធនឹងទ្រឹស្ដីនិងការអនុវត្តន៍ក្លែងបន្លំដែលបង្ហាញពីចំណាប់អារម្មណ៍ការផ្សាយពាណិជ្ជកម្មតាមអ៊ិនធឺណែតការចុចការផ្លាស់ប្តូរឬព្រឹត្តិការណ៍ទិន្នន័យដើម្បីបង្កើតប្រាក់ចំណូល។ ខណៈពេលដែលការក្លែងបន្លំការផ្សាយពាណិជ្ជកម្មត្រូវបានភ្ជាប់ជាទូទៅជាទូទៅជាមួយបដាផ្សព្វផ្សាយពាណិជ្ជកម្មនិងវីដេអូ"
            ],


        ];
        foreach($data as $record){
            \App\TypeOfPropertyReportTranslation::create($record);
        }
    }
}
