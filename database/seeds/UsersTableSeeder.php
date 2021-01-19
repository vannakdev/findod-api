<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'id' => 1,
                'first_name' => 'Admin',
                'last_name' => 'OPAPI',
                'email' => 'admin@gmail.com',
                'playerId' => 'ded6cf2c-dfed-4ad4-a7a4-50d38a8bd258',
                'password' => '$2y$10$M59slGcuew57O.AlMc6JfOH8TiZh8VblyZgyRCXbBWhxsgtZQHeJG',
                'userol_id' => 1,
                'setting'=>'{"gps": 1,"location":"11.564959 104.925930","language": "en","notification": {"sms": 1,"push": 1,"email": 1}}',
            ],
            [
                'id' => 2,
                'first_name' => 'User',
                'last_name' => 'OPAPI',
                'email' => 'user@gmail.com',
                'playerId' => 'ded6cf2c-dfed-4ad4-a7a4-50d38a8bd258',
                'password' => '$2y$10$M59slGcuew57O.AlMc6JfOH8TiZh8VblyZgyRCXbBWhxsgtZQHeJG',
                'userol_id' => 2,
                'setting'=>'{"gps": 1,"location":"11.564959 104.925930","language": "en","notification": {"sms": 1,"push": 1,"email": 1}}',
            ],
            [
                'id' => 3,
                'first_name' => 'Agent',
                'last_name' => 'OPAPI',
                'email' => 'agent@gmail.com',
                'playerId' => 'ded6cf2c-dfed-4ad4-a7a4-50d38a8bd258',
                'password' => '$2y$10$M59slGcuew57O.AlMc6JfOH8TiZh8VblyZgyRCXbBWhxsgtZQHeJG',
                'userol_id' => 3,
                'setting'=>'{"gps": 1,"location":"11.564959 104.925930","language": "en","notification": {"sms": 1,"push": 1,"email": 1}}',
            ],
        ]);
    }
}
