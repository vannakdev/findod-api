<?php

/**
 * Global class for system notification
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Users;
use Illuminate\Http\Request;
use App\Properties;
use Illuminate\Support\Carbon;
//use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Hash;
use Ifsnop\Mysqldump as IMysqldump;

class DatabaseController extends Controller {

    public function updateDatabase(Request $request) {
        return $this->updatePropertyPlan();
        if (Hash::check($request->input('password'), $user->password)) {

            $apikey = base64_encode(str_random(40));
            Users::where('username', $request->input('username'))->update(['api_token' => "$apikey"]);
            return response()->json(['status' => 'success', 'api_token' => $apikey]);
        } else {
            return response()->json(['status' => 'fail'], 401);
        }
    }

    public function updatePropertyPlan() {

        $properies = Properties::get();
        $list = ["1.jpg", "2.jpg", "3.jpg", "4.jpg", "5.jpg", "6.jpg", "7.jpg", "8.jpg", "9.jpg", "10.jpg"];

        foreach ($properies as $property) {
            $planes = [];
            $randomList = array_rand($list, 5);
            foreach ($randomList as $key) {
                array_push($planes, $list[$key]);
            }

            $property['pro_plan'] = json_encode($planes);
            $property->update();
        }

        return response()->json($properies);
    }

    /**
     * https://github.com/ifsnop/mysqldump-php
     * @return backup file
     */
    public function backup() {
        $date = Carbon::now()->format('Y-m-d_h-i');
        try {
            $dsn = "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_DATABASE');
            $dump = new IMysqldump\Mysqldump($dsn, env('DB_USERNAME'), env('DB_PASSWORD'));
            $dump->start('uploads/db_backup/lumenapi-' . $date . '.sql');
        } catch (\Exception $e) {
            return 'mysqldump-php error: ' . $e->getMessage();
        }
    }

    public function userResetPassword() {
        try {
            $password = app('hash')->make('Admin@123');
            $users = \App\Users::whereIn('email', ['admin@gmail.com', 'agent@gmail.com', 'user@gmail.com'])
                    ->update(['password' => $password]);
            return $this->getResponseData('1', 'Database reset succesufly.', $users);
        } catch (Exception $ex) {
            return $this->getResponseData('0', 'Database reset failed.', "");
        }
    }

    public function resetDatabase() {
        try {
            \App\Photo::query()->delete();
            \App\Plan::query()->delete();
            \App\Favorites::query()->delete();
            \App\PropertiesTranslation::query()->delete();
            \App\Ratings::query()->delete();
            \App\RequestViewing::query()->delete();
            \App\Properties::deleting(function($property) {
                $property->amenities()->detach();
            });
            return $this->getResponseData('1', 'Database reset succesufly.', "");
        } catch (Exception $ex) {
            return $this->getResponseData('0', 'Database reset failed.', "");
        }
    }

}
