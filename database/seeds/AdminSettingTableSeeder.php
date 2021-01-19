<?php

use Illuminate\Database\Seeder;

class AdminSettingTableSeeder extends Seeder
{
   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('admin_settings')->truncate();

        DB::table('admin_settings')->insert([
            [
                'id' => 1,
                'site_name' => 'FindOD',
                'meta_keyword' => 'findod real estate',
                'meta_description' => 'findod real estate',
                'aboutus' => 'Ocean Delight Investment Co., Ltd. (ODIC) was founded in March, 2017 with the purpose of bringing Cambodia many good investment projects and we make sure not only us but the whole community will earn benefit from these projects. Ocean Delight Real Estate is the first child company of ODIC.We are not only interested in the development of enterprise but also seeking to invest in the industry sector for foreigner and Khmer elitist.Besides being so professional with our service, our Ocean Delight Investment Team is also speak clients languages in order to ease the process of visiting and giving our clients a clear and valuable information about local and international market in Cambodia.',
                'site_address' => 'G-Floor of Emerald No. 64,Preah Norodom Blvd corner St.178, Phnom Penh.',
                'phone_number' => '023 927 ​​666',
                'email_id' => 'info@findod.com.kh',
                'facebook_link' => 'https://www.facebook.com/',
                'twitter_link' => 'https://www.linkedin.com/',
                'linkedid_link' => 'https://www.linkedin.com/',
                'google_link' => 'https://twitter.com',
                'apple_store' => 'https://www.apple.com/',
                'play_store' => 'https://play.google.com/store?hl=en'
			]
         ]);
    }

}


