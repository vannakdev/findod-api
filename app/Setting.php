<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    public $timestamps = false;

    // protected $public_setting = [
    //     'site_name',
    //     'site_address',
    //     'about_us',
    //     'meta_keyword',
    //     'meta_description',
    //     'email_id',
    //     'fav_icon',
    //     'social_facebook_link',
    //     'social_twitter_link',
    //     'social_linkedin_link',
    //     'social_google_plus_link',
    //     'content_about_us_link',
    //     'content_privacy_link',
    //     'content_term_link',
    //     'phone_number',
    //     'default_language_code',
    //     'default_currency_code'
    // ];

    // protected $private_setting = [
    //     'landing_page_youtube_video'
    // ];
}
