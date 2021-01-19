<?php

use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_role')->truncate();
        DB::table('user_role')->insert([
        ['id' => 1, 'title' => 'Administrator'],
        ['id' => 2, 'title' => 'User'],
        ['id' => 3, 'title' => 'Agent'],
        ]);
    }
}
