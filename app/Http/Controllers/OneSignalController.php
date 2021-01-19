<?php

/**
 * Global class for system notification
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use App\Http\Controllers\ResponderController;
use Validator;

class OneSignalController extends Controller {
//    public function send($data,$content,$playerId) {
//        
//        return self::sendMessage($data,$content, [$playerId]);
//        
////        return self::sendMessage(['foo' => "bar"], "Test Sending OneSignal Notification", ['ded6cf2c-dfed-4ad4-a7a4-50d38a8bd258']);
//    }

    /**
     * Onesignal push notification send to Specific Devices
     * @param array  [playerId, title, massage ]
     * @param array $play_ids Identify 
     * @param array $content the title of message and the description of notification
     * @return A property all information
     */
//    public function sendMessage(Array $additionalData, Array $play_ids, Array $content) {
    public function sendMessage(Array $request) {


        $setFields = array(
            'app_id' => env('ONESIGNAL_APP_ID'),
            'include_player_ids' => $request['playerId'],
            'headings' => ["title" => $request['title']],
            'contents' => ["en" => $request['message']],
            'data' => array(
                "action" => $request['action'],
                "message" => $request['message'],
            ),
        );

        $fields = json_encode($setFields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ODFiNDhjZjktMjZiNy00NjQ1LWJhMzItY2VjMWRjOWVmOTM4'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    /**
     * Onesignal push notification send to Specific Devices
     * @param Array Array three index [playerId, title, massage ]
     * @return Array A notification id
     * @reference https://documentation.onesignal.com/reference#section-specific-devices-usage
     */
    static function notifyPush(Array $request) {

        $setFields = array(
            'app_id' => env('ONESIGNAL_APP_ID'),
            'include_player_ids' => $request['playerId'],
            'headings' => ["title" => $request['title']],
            'contents' => ["en" => $request['message']],
            'data' => array(
                "action" => $request['action'],
                "message" => $request['data'],
            ),
            
        );

        $fields = json_encode($setFields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ODFiNDhjZjktMjZiNy00NjQ1LWJhMzItY2VjMWRjOWVmOTM4'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    public function notificationsByUserId($id) {
        $app_id = env('ONESIGNAL_APP_ID');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications/" . $id . "?app_id=" . $app_id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic ODFiNDhjZjktMjZiNy00NjQ1LWJhMzItY2VjMWRjOWVmOTM4'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        $return["allresponses"] = $response;
        return json_decode($return["allresponses"]);

    }

    public function createPlayer(Request $request) {
        $device_os = "9.1.3";
        $timezone = "-28800";
        $language = 'en';

        $validator = Validator::make($request->all(), ['device_type' => 'required|numeric|exists:device_types,index_number']);

        if ($validator->fails()): // check user input
            return $this->getResponseData("0", $validator->errors()->first(), '');
        endif;
        $fields = array(
            'app_id' => env('ONESIGNAL_APP_ID'),
            'language' => $language,
            'timezone' => $timezone,
            'device_os' => $device_os,
            'device_type' => $request->input('device_type')
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $return = json_decode($response);
        if (!isset($return->success)) {
            return $this->getResponseData("0", "Can not create player ID.", $return->errors);
        }
        return $this->getResponseData("1", "Player ID create successfully.", ['id' => $return->id]);
    }

    public function ViewNotification() {
        $app_id = env('ONESIGNAL_APP_ID');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://onesignal.com/api/v1/notifications?app_id=$app_id&offset=2",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic ODFiNDhjZjktMjZiNy00NjQ1LWJhMzItY2VjMWRjOWVmOTM4",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $getNotification = json_decode($response);
        dd($getNotification);
//        dd($noti->include_player_ids);
        $notificationList = [];
        foreach ($notifications->notifications as $noti) {
            if (isset($noti->include_player_ids)) {
                array_push($notificationList, $noti->include_player_ids);
            }
        }

        dd($notificationList);
    }

}
