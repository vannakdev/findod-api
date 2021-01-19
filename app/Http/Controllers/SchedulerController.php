<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Http\Controllers\NotificationController;
use App\Scheduler;

class SchedulerController extends Controller
{
    public function __construct()
    {
    }

    public function create($request)
    {

        //create feedback object and assign value from request's data
        $scheduler = new Scheduler();

        $scheduler->notification_manager_id = $request['notification_manager_id'];
        $scheduler->process_at = $request['process_at'];
        $scheduler->request = json_encode($request['request']);

        //commit save user into the database.
        if (! $scheduler->save()) {
            return $this->getResponseData('0', 'Schedule alert faild, please try again.', '');
        }

        return true;
    }

    public function runScheduler()
    {
        $schedulers = Scheduler::get();
        foreach ($schedulers as $processer) {
            $data = json_decode($processer['request'], true);
            $getUser = $data['user'];
            $user = \App\Users::find($getUser[0]['id']);

            $request = $data;
            $request['user'] = [$user];
            $notify = new NotificationController();
            $notify->sentNotify($request);

            return true;
        }
    }
}
