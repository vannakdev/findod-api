<?php

/**
 * Global class for system notification
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Properties;
use App\RequestViewing;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Description of newPHPClass
 *
 * @author OU Sophea : ODIC
 */
class RequestViewingController extends Controller {

    public function __construct() {
        
    }

    public function create(Request $request) {
         $user = Auth::user();
         $validator = Validator::make($request->all(), [
                    'property_id' => 'required|exists:properties,id,pro_active,1,deleted_at,NULL'
                                   . '|unique:properties,id,null,pros_use_id,pro_use_id,'.$user->id,// when user try to request their hosting property
                    'description' => 'required|max:250'
                        ], [
                    'property_id.exists' => 'Request property not found.',
                     'property_id.unique' => 'You can not request view your hosting property.'
        ]);
        if ($validator->fails()) {
            return $this->getResponseData("0", $validator->errors()->first(),"");
        }

        $requestViewing = new RequestViewing();
//        =======ON UI must ready check when view detail property============
        $getRequestViewing = $requestViewing->where('property_id', $request->property_id)
                        ->where('users_id', $user->id)->first(); //check if user ready sending the review

        if ($getRequestViewing != NULL) {// property ready request viewing
            return $this->getResponseData("0", "You ready request viewing this property.", $getRequestViewing);
        }

        //================Record review request=======================
        $requestViewing->property_id = $request->input('property_id');
        $requestViewing->users_id = $user->id;
        $requestViewing->description = $request->input('description');

        if (!$requestViewing->save()) {
            return $this->getResponseData("0", "Sending notification fails.", "");
        }


        //==================================
//        ==============Record notification===============
//        $setNotification = [
//            'user_id' => $property->pro_use_id
//                ,'sender_id' => $user->id
//            ,'properties_id' => $property->id
//            ,'comments' => $request->input('description')
//        ];
//        $playerId = [$property->users()->first()->playerId];
//        $storeNotify = new NotificationController();
//        $getNotifi = $storeNotify->storeRating($setNotification, $playerId);
//
//        if (!$getNotifi):
//            return $this->getResponseData("0", "Sending notification fails.", "");
//        endif;
        //======================================================



        return $this->getResponseData("1", "Sending notifiction successfully.", '');
    }

}
