<?php
/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Feedback;
use App\Http\Controllers\ResponderController;
use Illuminate\Http\Request;
use Validator;

/**
 * Description of newPHPClass.
 *
 * @author OU Sophea : ODIC
 */
class FeedbackController extends Controller
{
    public function __construct()
    {
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:50|regex:/^[\pL\s\-]+$/u',
                    'email' => 'required|email|max:50',
                    'message' => 'required|max:300',
                        ]
        );

        if ($validator->fails()) {
            return $this->getResponseData('0', 'Data validation failed.', $validator->errors()->first());
        }

        //create feedback object and assign value from request's data
        $feedback = new Feedback();

        $feedback->email = $request->input('email');
        $feedback->name = $request->input('name');
        $feedback->message = $request->input('message');

        //commit save user into the database.
        if (! $feedback->save()) {
            return $this->getResponseData('0', 'Feedback submit faild, please try again.', '');
        }

        return $this->getResponseData('1', 'User have been created successfully', $feedback->fresh());
    }
}
