<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Http\Controllers\OneSignalController;
use App\Http\Controllers\ResponderController;
use App\Notification;
use App\NotificationManager;
use App\Properties;
use App\Setting;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    protected static $viewing_type = 1;
    protected static $rating_type = 2;
    protected static $number_per_page = 25;

    public function __construct()
    {
    }

    public function sentNotify($request)
    {
//        return  true;// Skip this ducrint development
        $notificationTypes = NotificationManager::with('notifyContent')
                ->where('module', $request['module'])
                ->where('event', $request['event'])
                ->get();

        foreach ($notificationTypes as $notify) {
            $fun = 'notify'.$notify->type;
            try {
                $this->$fun($request['user'], $notify);
            } catch (Exception $ex) {
                return 'Error '.$ex;
            }
        }

        return true;
    }

    /*
     * Send notification by Push on moble
     * @param   array $user
     * @param   object $notify
     * @param   integer [$propertyId]
     * @param   interger [$senderId]
     * @return \Illuminate\Http\Response
     */

    public function notifyPush($users, $notify, $senderId = null, $respond = null)
    {
//        return TRUE; // Skip this during development
        if (! is_array($users)) :
            return $this->notifyPushProcess($users, $notify, $senderId = null, $respond); else :
            foreach ($users as $user) :
                $this->notifyPushProcess($user, $notify, $senderId = null, $respond);
        endforeach;
        endif;
    }

    /**
     * @param type $user
     * @param type $notify
     * @param type $propertyId
     * @param type $senderId
     * @param type $respond
     */
    public function notifyPushProcess($user, $notify, $senderId = null, $respond = null)
    {
        $userSetting = $user->setting;
        //==========if user anable push notification on mobile==============
        $playerId = [$user->playerId];

        if ($userSetting->notification->push == 0) :
            return false; //user not allow push notification
        endif;
        $sendPushNote = $this->sendingPushNotify($playerId, $notify, $userSetting->language, $respond);

        if (isset($sendPushNote->id)) :
            $notification_id = $sendPushNote->id;
        $status = 1; else :
            $status = 0;
        $notification_id = '';
        endif;

        // =============================================
        $this->storeNotification($user->id, $notify->id, $status, $notify->property_id, $senderId, $notification_id);
    }

    /**
     * @param type $playerId
     * @param type $notify
     * @param type $language
     * @return int
     */
    public function sendingPushNotify($playerId, $notify, $language, $respond = null)
    {
//        dd([$playerId, $notify, $language]);
        foreach ($notify->notifyContent as $notifyContent) :
            if ($notifyContent->locale == $language) {
                $title = $notifyContent->title;
                $content = $notifyContent->content;
                $message = $content[0];

                $getRequest = [
                    'playerId' => $playerId,
                    'title' => $title,
                    'message' => $message,
                    'data' => $respond,
                    'action' => $notify->module,
                ];

                $notifyPush = OneSignalController::notifyPush($getRequest);

                if (isset($notifyPush->errors)) :
                    return 0;
                endif;

                return $notifyPush;
            }
        endforeach;
    }

    /**
     * @param type $users
     * @param type $notify
     * @param type $emailParam
     * @param type $propertyId
     * @param type $senderId
     */
    public function notifyEmail($users, $notify, $emailParam = null, $propertyId = null, $senderId = null)
    {
        if (! is_array($users)) {
            $this->notifyEmailProcess($users, $notify, $emailParam = null, $propertyId = null, $senderId = null);
        } else {
            foreach ($users as $user) :
                $this->notifyEmailProcess($user, $notify, $emailParam = null, $propertyId = null, $senderId = null);
            endforeach;
        }
    }

    public function notifyEmailProcess($user, $notify, $emailParam = null, $propertyId = null, $senderId = null)
    {
        $userSetting = $user->setting;
        //==========if user anable email notification ==============
        if ($userSetting->notification->email) {
            $seddingStatus = $this->sendEmailNotify($user, $notify, $userSetting->language, $emailParam);

            $this->storeNotification($user->id, $notify->id, $seddingStatus, $propertyId, $senderId);
        }
    }

    public function sendEmailNotify($user, $notify, $language, $emailParam = null)
    {
        if ($user->email == null) :
            return false;
        endif;
        $email = $user->email;
        $name = ucfirst($user->first_name).' '.ucfirst($user->last_name);

        /* Application Settings */
        $settings = Setting::get(['key', 'value'])->toArray();
        $keys = array_pluck($settings, 'key');
        $values = array_pluck($settings, 'value');
        $setting_data = array_combine($keys, $values);

//        if ($emailParam != NULL):
//            foreach ($emailParam as $key => $value):
//                $param[$key] = $value;
//            endforeach;
//        endif;

        foreach ($notify->notifyContent as $notifyContent) :
            if ($notifyContent->locale == $language) {
                $subject = $notifyContent->title;
                $template = $notifyContent->template;
                $content = json_decode($notifyContent->content);
                // convert from object data to array parameter
                foreach ($content->param as $key => $value) :
                    $param[$key] = $value;
                endforeach;
                $param['name'] = $name;
                $param['facebook_link'] = $setting_data['social_facebook_link'];
                $param['social_twitter_link'] = $setting_data['social_twitter_link'];
                $param['social_google_plus_link'] = $setting_data['social_google_plus_link'];
                $param['social_linkedin_link'] = $setting_data['social_linkedin_link'];
                $param['app_store_ios'] = $setting_data['app_store_ios'];
                $param['play_store_android'] = $setting_data['play_store_android'];
                $param['social_youtube_link'] = 'https://www.youtube.com/channel/UCnplGbZBQd5nUbkSRx0QqOw';
                try {
                    $result = Mail::to($email)->send(new \App\Mail\EmailNotification($subject, $param, $template));
//                    return dd($result);
                } catch (\Exception $e) {
                    return 0;
//                    throw new \Exception('Error: can not send mail. There are some problems with Mail Server');
                }
            }

        endforeach;

        return 0;
    }

    /*
     * Store sending notification
     * @param $reciver
     * @param $notifyId
     * @param $status
     * @param $propertyId
     * @param $senderId
     */

    public function storeNotification($reciver, $notifyId, $status, $propertyId = null, $senderId = null, $notification_id = null)
    {
        $notification = new Notification();
        $notification->sender_id = ($senderId != null ? $senderId : 1);
        $notification->user_id = $reciver;
        if ($propertyId != null) :
            $notification->properties_id = $propertyId;
        endif;
        $notification->comments = '';
        $notification->notification_manager_id = $notifyId;
        $notification->status = $status;
        $notification->notification_id = ($notification_id != null ? $notification_id : '');

        if (! $notification->save()) :
            return false;
        endif;

        return true;
    }

    public function notifySMS()
    {
        dd('Send sms');
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRating(array $request, $playerId)
    {
        $sendMessage = new OneSignalController();
        $notify = \App\Notification_type::find(2);

        $content = ['title' => $notify['title'],
            'message' => $notify['content'],
            'playerId' => $playerId, ];

        $pushNotification = $sendMessage->sendMessage($content);
        if (! $pushNotification) {
            return $pushNotification;
        }
        $request['notification_type_id'] = self::$rating_type;
        $storeNotify = Notification::create($request);

        return [$storeNotify, $pushNotification];
    }

    /**
     * Display the specified resource.

     *

     * @param  \App\Notification  $notification

     * @return \Illuminate\Http\Response
     */
    public function getNotificationsByUserId()
    {
        $responder = new ResponderController;
        $user = Auth::user();
        $notifications = Notification::
                where('user_id', $user->id)
                ->with('notification_type', 'sender')
                ->latest();

//        if ($notifications) {
//            return $responder->returnMessage(0, null, null, [], "Not notification founded.");
//        }
        return $responder->returnMessage(1, null, null, $notifications);
    }

    public function show($id)
    {
        $OneSignal = new OneSignalController();

        $viewNotification = $OneSignal->notificationsByUserId($id);
        dd($viewNotification->data);
    }

    /**
     * Display the specified resource.

     *

     * @param  \App\Notification  $notification

     * @return \Illuminate\Http\Response
     */
    public function getNotificationsById($notifyId)
    {
        $responder = new ResponderController;
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
                ->where('id', $notifyId)
                ->with('notification_type', 'sender')
                ->first();
        if (! $notifications) {
            return $responder->returnMessage(0, null, null, [], 'Not notification founded.');
        }

        return $responder->returnMessage(1, null, null, $notifications);
    }

    public function showAll()
    {
        $responder = new ResponderController;
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
                        ->with([
                            'notification_manager' => function ($q) {
                                $q->with('notifyContent')->where('type', 'Push');
                            }, ]
                        )
//                        ->with('notification_type', 'sender')
                        ->paginate(self::$number_per_page)->toArray();
        if (empty($notifications['data'])) {
            return $responder->returnMessage(0, null, null, [], 'Not notification founded.');
        }

        return $responder->returnMessage(1, null, null, $notifications['data']);
    }

    /**
     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Notification  $notification

     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $notification->update($request->all());

        return redirect()->route('products.index')
                        ->with('success', 'Notification updated successfully');
    }

    /**
     * Remove the specified resource from storage.

     *

     * @param  \App\Notification  $notification

     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('products.index')
                        ->with('success', 'Notification deleted successfully');
    }

    public function nearbyPost(Request $request)
    {
        $distance = 10;
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $propertyId = $request->input('propertyId');
        $this->sendNearbyUser($propertyId, $lat, $lng, $distance);
    }

    /**
     * Sent notification to user have location nearby hosting property.
     * @param  \App\Notification  $notification
     * @return void
     */
    public function sendNearbyUser($propertyId, $lat, $lng, $distance)
    {
        $userList = $this->getNearByUser($lat, $lng, $distance);

//        =====================Check notification============
        $notificationTypes = NotificationManager::with('notifyContent')->where('module', 'PostNearByUsers')->where('event', 'create')->get();
        foreach ($notificationTypes as $notify) {
            if ($notify->type == 'Push') :
                $senderId = 1;
            $this->notifyPush($userList, $notify, $propertyId, $senderId);
            endif;

            if ($notify->type == 'Email') :
                $emailParams = ['propertyId' => $propertyId];
            $this->notifyEmail($userList, $notify, $emailParams, $propertyId, $senderId);
            endif;
        }
    }

    /**
     * Sent notification to new register user.
     * @param  \App\Notification  $notification
     * @return void
     */
    public function sendWelcomeUser(Users $user)
    {
//        =====================Check notification============
        $notificationTypes = NotificationManager::with('notifyContent')->where('module', 'RegisterUsers')->where('event', 'create')->get();

        foreach ($notificationTypes as $notify) {
            if ($notify->type == 'Push') :
                $senderId = 1;
            $this->notifyPush($user, $notify, $senderId);
            endif;

            if ($notify->type == 'Email') :
//                $senderId = 1;
//                $propertyId = null;
                $getContent = $notify->notifyContent;
            if ($getContent == null) {
                return true;
            }
            foreach ($getContent as $params) :
                    $emailParams = json_decode($params->content);
            endforeach;
//                dd($emailParams);
//                foreach ($emailParams->param as $key => $value):
//                    $param[$key] = json_encode($value);
//                endforeach;
//                dd($param);
//                $getUser  = $param['object'];
//                $emailParams = ['userName' => $user->first_name." ".$user->last_name];

            $this->notifyEmail($user, $notify, $emailParams->param);
            endif;
        }
    }

    /**
     * @return type
     */
    public function notifyPasswordChangeConfirm($user)
    {
//        =====================Check notification============
        $notificationTypes = NotificationManager::with('notifyContent')->where('module', 'PasswordChnageConfirm')->where('event', 'create')->get();

        foreach ($notificationTypes as $notify) {
            if ($notify->type == 'Email') :
                if ($notify->notifyContent == null) {
                    return dd("Notification can't perform.");
                }
            $senderId = 1;
            $propertyId = null;
            $getContent = $notify->notifyContent;
            foreach ($getContent as $params) :
                    $emailParams = json_decode($params->content);
            endforeach;
            $this->notifyEmail($user, $notify, $emailParams->param);
            endif;
        }
    }

    /**
     * @return type
     */
    public function notifyDeactivateAccount($email)
    {
        //$email = 'ousophea@gmail.com';
        $user = Users::where('email', $email)->first();
//        =====================Check notification============
        $notificationTypes = NotificationManager::with('notifyContent')->where('module', 'DeactivateAccount')->where('event', 'create')->get();

        foreach ($notificationTypes as $notify) {
//            if ($notify->type == 'Push'):
//                $senderId = 1;
//                $this->notifyPush($user, $notify, $senderId);
//            endif;
            if ($notify->type == 'Email') :
                if ($notify->notifyContent == null) {
                    return dd("Notification can't perform.");
                }
            $senderId = 1;
            $propertyId = null;
            $getContent = $notify->notifyContent;
            foreach ($getContent as $params) :
                    $emailParams = json_decode($params->content);
            endforeach;
            $this->notifyEmail($user, $notify, $emailParams->param);
            endif;
        }
    }

    /**
     * Sent notification to new register user.
     * @param  \App\Notification  $notification
     * @return void
     */
    public function sendRatingAndReview($rating, $user)
    {
//        =====================Check notification============
        $notificationTypes = NotificationManager::with('notifyContent')->where('module', 'RattingProperty')->where('event', 'create')->get();
//        $property = Properties::find($rating->property_id);
//        $user = [$property->users()->first()];
        foreach ($notificationTypes as $notify) {
            if ($notify->type == 'Push') :
                $senderId = 1;
            $this->notifyPush($user, $notify, $senderId, $rating);
            endif;

            if ($notify->type == 'Email') :
                $getContent = $notify->notifyContent;
            foreach ($getContent as $params) :
                    $emailParams = json_decode($params->content);
            endforeach;

            foreach ($emailParams->param as $key => $value) :
                    $param[$key] = json_encode($value);
            endforeach;

            $this->notifyEmail($user, $notify, $emailParams->param);
            endif;
        }
    }

    /*
     * Return list of use nearby given property location
     */

    private function getNearByUser($lat, $lng, $distance)
    {
        $nearByUsers = Notification::getUserByDistance($lat, $lng, $distance);
        if ($nearByUsers == '') {
            return [];
        }
        $ids = [];
        foreach ($nearByUsers as $user) {
            array_push($ids, $user->id);
        }
        $users = Users::whereIn('id', $ids)->get();
        $userList = [];
        foreach ($users as $user) :
            array_push($userList, $user);
        endforeach;

        return $userList;
    }
}
