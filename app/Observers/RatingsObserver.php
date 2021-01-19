<?php

namespace App\Observers;

use App\Ratings;
use App\Http\Controllers\NotificationController;
use App\Properties;
use Illuminate\Support\Facades\DB;

class RatingsObserver {

    public $module = 'rating';

    /**
     * Listen to the rating created event.
     *
     * @param  \App\Rating  $rating
     * @return void
     */
    public function created(Ratings $rating) {

        $property = Properties::find($rating->property_id);
        $user = [$property->users()->first()];
//
//        foreach ($user as $key) {
//            $use_id = $key['id'];
//        }
//        $request = [
//            'module' => $this->module,
//            'event' => 'create',
//            'user' => $user,
//            'sending_notification' => [
//                'user_id' => $use_id,
//                'sender_id' => $rating->user_id,
//                'properties_id' => $rating->property_id,
//                'comments' => $rating->comments
//            ]
//        ];
//        $notify = new NotificationController();
//        $notify->sentNotify($request);
        $notify = new NotificationController();
        $notify->sendRatingAndReview($rating,$user);
        
        ///=============Update property review counter=============
        $property->pro_rating = Ratings::avg('stars');
        $property->comment_count++;
        $property->timestamps = false;
        $property->save();
//        Properties::where('id', $rating->property_id)->update([
//            'pro_rating' => Ratings::avg('stars'),
//            'comment_count'=> 1
//            ]);
        //==============================================
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
