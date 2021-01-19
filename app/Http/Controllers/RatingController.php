<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Http\Controllers\ResponderController;
use App\Ratings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Description of newPHPClass.
 *
 * @author OU Sophea : ODIC
 */
class RatingController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Insert or update rating recode with user information.
     * @param  array  $data
     * @return user input validation or insert/ update property stars
     */
    public function create(Request $request)
    {
        $responder = new ResponderController;
        $user = Auth::user();
        $checkInput = $responder->formValidater($request, ['property_id' => 'required|numeric', 'stars' => 'required|numeric|max:5']);
        if ($checkInput != null):// check user input
            return $responder->returnMessage(0, null, null, '', $checkInput);
        endif;

        $property = \App\Properties::find($request->input('property_id'));

        if ($property == null):
            return $this->getResponseData('0', 'Property not found.', '');
        endif;

        //create new rating object and assign value from request's data
        $rate = Ratings::firstOrCreate(
                        ['property_id' => $request->input('property_id'), 'user_id' => $user->id],
                ['stars' => $request->input('stars'), 'comments' => $request->input('comments')]
        );
        if (! $rate) {
            return $this->getResponseData('0', 'Property rating can not created.', $rate);
        }

        return $this->getResponseData('1', 'Property rating have been created successfully', ['rating' => $rate]);
    }

    public function checkRating($property_id)
    {
        $user = Auth::user();
        $getRating = Ratings::where('property_id', $property_id)
                ->where('user_id', $user->id)
                ->first();
        if ($getRating != null) { // Check user email with database
            return $this->getResponseData('0', 'You ready rating the property', $getRating);
        }

        return $this->getResponseData('1', 'Start rating property.', '');
    }

    /**
     * Insert or update rating recode with user information.
     * @param  int  $propertyId request property id
     * @return List of counter for each star request and rate for the property
     */
    public function getRatingCounter($propertyId)
    {
        $responder = new ResponderController;
//        $results = DB::select("call proGetNumberOfStarRate($propertyId)");
        $rating = new Ratings();
        $results = $rating->getPropertyRate($propertyId);

        if ($results != null) {
            return $responder->returnMessage(1, null, null, $results);
        } else {
            return $responder->returnMessage(0, null, null, [], "Don't have any rating yet.");
        }
    }

    /**
     * Get list you review by user.
     * @param  int  $id request property id
     * @return List of array (status,message,and review list)
     */
    public function getReviews($id, Ratings $ratings)
    {
        $responder = new ResponderController;
        try {
            $rating = $ratings->with(['users' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'photo');
            }])
                    ->where('property_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();

            return $responder->returnMessage(1, null, null, $rating);
        } catch (Exception $ex) {
            return $responder->returnMessage(0, 'Property', 1);
        }
    }
}
