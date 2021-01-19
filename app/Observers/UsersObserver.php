<?php

namespace App\Observers;

use App\Http\Controllers\NotificationController;
use App\Users;

class UsersObserver
{
    public $module = 'RegisterUsers';

    /**
     * Listen to the rating created event.
     *
     * @param  \App\Users $user
     * @return void
     */
    public function created(Users $user)
    {

//set an date and time to work with
        //==================Old function =========================
//        $data = [
//            'notification_manager_id' => 5,
//            'process_at' => $process_at,
//            'request' => $request];
//        $scheduler = new SchedulerController();
//        return $scheduler->create($data);
//        $request = [
//            'module' => $this->module,
//            'event' => 'create',
//            'user' => [$user],
//            'sending_notification' => [
//                'user_id' => $user->id,
//                'sender_id' => null,
//                'properties_id' => null,
//                'comments' => "Welcome to Ocean Property."
//            ]
//        ];
//
//        $notify = new NotificationController();
//        $notify->sentNotify($request);
//        return true;
        //=============New version ==================
        $notify = new NotificationController();

        $notify->sendWelcomeUser($user);

        return true;
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\Rating  $rating
     * @return void
     */
    public function deleting(Rating $rating)
    {
        //
    }
}
