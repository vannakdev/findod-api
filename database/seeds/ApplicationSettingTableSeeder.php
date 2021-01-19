<?php

use Illuminate\Database\Seeder;

class ApplicatoinSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Gallery::create([
        //     'user_id' => 1,
        //     'key' => 'mobile_application_show_case',
        //     'title' => 'Mobile Application Show Case'
        // ]);

        if (! App\AppSetting::where('key', 'landing_page_youtube_video')->first()) {
            $setting = new App\AppSetting;
            $setting->key = 'landing_page_youtube_video';
            $setting->data_type = 'String';
            $setting->value = 'https://youtube/embed/video_id';
            $setting->save();
        }

        $settings = [
            'site_name' => 'Application Name',
            'site_address' => 'Application Name',
            'about_us' => 'Describe something about your Business ',
            'meta_keyword' => 'keyword for Search Engine Optimization ',
            'meta_description' => 'Tell about your Business  in more detail',
            'phone_number' => '010866035',
            'email_id' => 'email@business.example',
            'fav_icon' => '',
            'social_facebook_link' => 'https://facebook.com/#',
            'social_twitter_link' => 'https://twitter.com/#',
            'social_linkedin_link' => 'https://linkedin.com/#',
            'social_google_plus_link' => 'https://plus.google.com/#',
            'social_instagram_link' => 'https://www.instagram.com/#',
            'content_about_us_link' => 'https://yourdomain.com/about-us',
            'content_privacy_link' => 'https://yourdomain.com/privacy',
            'content_term_link' => 'http://yourdomain.com/term',
            'content_property_hosting_policy' => 'http://yourdomain.com/property-hosting-policy',
            'default_language_code' => 'en',
            'default_currency_code' => 'usd',
            'app_store_link'=> 'https://plus.google.com/#',
            'play_store_link'=>'https://plus.google.com/#',
        ];

        foreach ($settings as $key => $value) {
            if (App\AppSetting::where('key', $key)->first()) {
                continue;
            }
            $setting = new App\AppSetting;
            $setting->key = $key;
            $setting->data_type = 'String';
            $setting->value = $value;
            $setting->save();
        }
    }
}
