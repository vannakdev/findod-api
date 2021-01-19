<?php

use Illuminate\Database\Seeder;

class NotificationManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notification_manager')->truncate();
        DB::table('notification_manager')->insert([
            [
                'module' => 'rating',
                'event' => 'create',
                'type' => 'Push', ],
            [
                'module' => 'RequestViewing',
                'event' => 'create',
                'type' => 'Push', ],
            [
                'module' => 'RequestViewing',
                'event' => 'create',
                'type' => 'Email', ],
            [
                'module' => 'rating',
                'event' => 'create',
                'type' => 'Email', ],
            [
                'module' => 'RegisterUsers',
                'event' => 'create',
                'type' => 'Email', ],
            [
                'module' => 'RegisterUsers',
                'event' => 'create',
                'type' => 'Push', ],
            [
                'module' => 'PostNearByUsers',
                'event' => 'create',
                'type' => 'Push', ],
            [
                'module' => 'PostNearByUsers',
                'event' => 'create',
                'type' => 'Email', ],
                [
                    'module' => 'PasswordChnageConfirm',
                    'event' => 'create',
                    'type' => 'Email', ],
                        [
                    'module' => 'DeactivateAccount',
                    'event' => 'create',
                    'type' => 'Email', ],

        ]);
    }
}
