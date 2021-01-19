<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\PasswordReset;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    //Number of fail verify attempt
    protected static $threshold_count = 5;
    //number of minute for lock account
    protected static $lockout_duration = 120;
    //Number of minute for allow verify pro
    protected static $threshold_duration = 15;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Generate the 6 digit for given email address.
     * @param $email, $token
     * @return true on token match, false on token mismatch
     */
    public static function verify_token($email, $token)
    {
        $password_reset_obj = PasswordReset::where('email', $email)->first();

        if (! $password_reset_obj) {
            return false;
        }

        if (strcmp($password_reset_obj->token, $token) !== 0) {
            return false;
        }

        return true;
    }

    public static function change_password($user_email, $new_password)
    {
        $user = User::where('email', $user_email)
                        ->where('status', 1)->first();

        if (! $user) {
            return false;
        }

        $user->password = app('hash')->make($new_password);

        if (! $user->save()) {
            return false;
        }

        return true;
    }

    public static function delete_all_token($user_email)
    {
        PasswordReset::where('email', $user_email)->delete();
    }

    /**
     * Generate the 6 digit for given email address.
     *
     * @return string 6 digit token on sucess, false on fail
     */
    public static function generateResetPasswordToken($user_email)
    {

        //$passwordReset = PasswordReset::where('email', $user_email)->first();

        self::delete_all_token($user_email);

        $passwordReset = new PasswordReset();
        $token = rand(100000, 999999);
        $passwordReset->email = $user_email;
        $passwordReset->token = $token;
        $passwordReset->created_at = Carbon::now();
        $passwordReset->threshold_count = self::$threshold_count;
        $passwordReset->lockout_duration = self::$lockout_duration;
        $passwordReset->threshold_duration = self::$threshold_duration;

        if (! $passwordReset->save()) {
            return false;
        }

        return $passwordReset->token;
    }

    /**
     * Generate link to reset password.
     */
    public static function generateResetPasswordLink($user_email)
    {
        self::delete_all_token($user_email);
        $passwordReset = new PasswordReset();
        $token = base64_encode(microtime());
        $passwordReset->email = $user_email;
        $passwordReset->token = $token;
        $passwordReset->created_at = Carbon::now();
        $passwordReset->threshold_count = self::$threshold_count;
        $passwordReset->lockout_duration = self::$lockout_duration;
        $passwordReset->threshold_duration = self::$threshold_duration;

        if (! $passwordReset->save()) {
            return false;
        }

        return env('WEPSITE_URL').$passwordReset->token.'?email='.urlencode($user_email);
    }

    /**
     * Send Reset 6 digit token to $email.
     *
     * @return true on Success, Exception
     */
    public static function sendResetPasswordEmail($email, $token, $name = null)
    {

        /* Application Settings */
        $settings = Setting::get(['key', 'value'])->toArray();
        $keys = array_pluck($settings, 'key');
        $values = array_pluck($settings, 'value');
        $setting_data = array_combine($keys, $values);

        $param['name'] = $name;
        $param['token'] = $token;
        $param['facebook_link'] = $setting_data['social_facebook_link'];
        $param['social_twitter_link'] = $setting_data['social_twitter_link'];
        $param['social_google_plus_link'] = $setting_data['social_google_plus_link'];
        $param['social_linkedin_link'] = $setting_data['social_linkedin_link'];
        $param['app_store_ios'] = $setting_data['app_store_ios'];
        $param['play_store_android'] = $setting_data['play_store_android'];
        $param['social_youtube_link'] = 'https://www.youtube.com/channel/UCnplGbZBQd5nUbkSRx0QqOw';

        try {
            Mail::to($email)->send(new \App\Mail\ResetPasswordRequest($param));

            return true;
        } catch (\Exception $e) {
            throw new \Exception('Error: can not send mail. There are some problems with Mail Server');
        }
    }

    /**
     * Send Reset 6 digit token to $email.
     *
     * @return true on Success, Exception
     */
    public static function sendLinkResetPasswordEmail($email, $link, $name)
    {

         /* Application Settings */
        $settings = Setting::get(['key', 'value'])->toArray();
        $keys = array_pluck($settings, 'key');
        $values = array_pluck($settings, 'value');
        $setting_data = array_combine($keys, $values);

        $param['name'] = $name;
        $param['link'] = $link;
        $param['facebook_link'] = $setting_data['social_facebook_link'];
        $param['social_twitter_link'] = $setting_data['social_twitter_link'];
        $param['social_google_plus_link'] = $setting_data['social_google_plus_link'];
        $param['social_linkedin_link'] = $setting_data['social_linkedin_link'];
        $param['app_store_ios'] = $setting_data['app_store_ios'];
        $param['play_store_android'] = $setting_data['play_store_android'];
        $param['social_youtube_link'] = 'https://www.youtube.com/channel/UCnplGbZBQd5nUbkSRx0QqOw';

        try {
            Mail::to($email)->send(new \App\Mail\ResetPasswordLink($param));

            return true;
        } catch (\Exception $e) {
            throw new \Exception('Error: can not send mail. There are some problems with Mail Server');
        }
    }
}
