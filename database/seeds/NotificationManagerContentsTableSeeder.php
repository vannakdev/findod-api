<?php

use Illuminate\Database\Seeder;

class NotificationManagerContentsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('notification_manager_contents')->truncate();

        DB::table('notification_manager_contents')->insert([
            [
                'notification_manager_id' => 1,
                'locale' => "en",
                'title' => 'Rating property',
                'template' => 'rating',
                'content' => '["Someone rating your property"]'],
            [
                'notification_manager_id' => 4,
                'locale' => "km",
                'title' => 'លក្ខណៈសម្បត្តិវាយតម្លៃ',
                'template' => 'rating',
                'content' => '{"param": {"emailBody": "នរណាម្នាក់វាយតម្លៃអចលនទ្រព្យរបស់អ្នក", "emailTitle": "លក្ខណៈសម្បត្តិវាយតម្លៃ"}}'],
            [
                'notification_manager_id' => 4,
                'locale' => "en",
                'title' => 'Somone rating your property',
                'template' => 'rating',
                'content' => '{"param": {"emailBody": "Testing body", "emailTitle": "Test title"}}'],
            [
                'notification_manager_id' => 1,
                'locale' => "km",
                'title' => 'លក្ខណៈសម្បត្តិវាយតម្លៃ',
                'template' => 'rating',
                'content' => '["នរណាម្នាក់វាយតម្លៃទ្រព្យរបស់អ្នក"]'],
            [
                'notification_manager_id' => 2,
                'locale' => "km",
                'title' => 'ស្នើសុំការមើលអចលនទ្រព្យ',
                'template' => 'rating',
                'content' => '["នរណាម្នាក់វាយតម្លៃអចលនទ្រព្យរបស់អ្នក"]'],
            [
                'notification_manager_id' => 3,
                'locale' => "km",
                'title' => 'ស្នើសុំការមើលអចលនទ្រព្យ',
                'template' => 'rating',
                'content' => '{"param": {"emailBody": "នរណាម្នាក់វាយតម្លៃអចលនទ្រព្យរបស់អ្នក", "emailTitle": "លក្ខណៈសម្បត្តិវាយតម្លៃ"}}'],
            [
                'notification_manager_id' => 2,
                'locale' => "en",
                'title' => 'Request viewing property',
                'template' => 'rating',
                'content' => '["Somone request to viewing you hosting property"]'],
            [
                'notification_manager_id' => 3,
                'locale' => "en",
                'title' => 'Request viewing property',
                'template' => 'rating',
                'content' => '{"param": {"emailBody": "Somone request to viewing you hosting property", "emailTitle": "Request Viewing Posting Property"}}'],
            [
                'notification_manager_id' => 5,
                'locale' => "en",
                'title' => 'Welcome to Ocean Property',
                'template' => 'welcome',
                'content' => '{"param": {"emailBody": "Welcome body", "emailTitle": "Welcome to Ocean property"}}'],
            [
                'notification_manager_id' => 6,
                'locale' => "en",
                'title' => 'Welcome to Ocean Property',
                'template' => 'welcome',
                'content' => '["Welcome to Ocean Property"]'],
            [
                'notification_manager_id' => 7,
                'locale' => "en",
                'title' => 'New property',
                'template' => 'welcome',
                'content' => '["New update property nearby your location"]'],
            [
                'notification_manager_id' => 8,
                'locale' => "en",
                'title' => 'New property near by posted',
                'template' => 'rating',
                'content' => '{"param": {"emailBody": "Welcome body", "emailTitle": "Welcome to Ocean property"}}'],
            [
                'notification_manager_id' => 9,
                'locale' => "km",
                'title' => 'Password change confirmation',
                'template' => 'passwordChnageConfirm',
                'content' => '{"param": {"emailBody": "Welcome body", "emailTitle": "Welcome to Ocean property"}}'],
            [
                'notification_manager_id' => 9,
                'locale' => "en",
                'title' => 'Password change confirmation',
                'template' => 'passwordChnageConfirm',
                'content' => '{"param": {"emailBody": "Welcome body", "emailTitle": "Welcome to Ocean property"}}'],
            [
                'notification_manager_id' => 10,
                'locale' => "en",
                'title' => 'Deactivate Account',
                'template' => 'deactivate',
                'content' => '{"param": {"object": ["users"], "emailBody": "Welcome body", "emailTitle": "Welcome to Ocean property"}}'],
        ]);
    }

}
