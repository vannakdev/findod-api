<?php

namespace App\Observers;

use App\RequestViewing;
use App\Http\Controllers\NotificationController;

class RequestViewingObserver {

    public $module = 'RequestViewing';

    /**
     * Listen to the rating created event.
     *
     * @param  \App\RequestViewing  $RequestViewing
     * @return void
     */
    public function created(RequestViewing $RequestViewing) {
        
        $property = \App\Properties::find($RequestViewing->property_id);
        $user = [$property->users()->first()];

        foreach ($user as $key){
            $use_id = $key['id'];
        }
        $request = [
            'module' => $this->module,
            'event' => 'create',
            'user' => $user,
            'sending_notification' => [
                'user_id' =>$use_id,
                'sender_id' => $RequestViewing->user_id,
                'properties_id' => $RequestViewing->property_id,
                'comments'=>$RequestViewing->description
            ]
        ];

        
        $notify = new NotificationController();
        $notify->sentNotify($request);
        return true;
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  \App\Rating  $rating
     * @return void
     */
    public function deleting(Rating $rating) {
        //
    }

}
