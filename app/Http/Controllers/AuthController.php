<?php
/**
 * Global class for system notification
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ResponderController;

class AuthController extends Controller {

    public function __construct() {
        
    }

    public function authenticate(Request $request) {
        $user = $this->authenticating($request->input('username'), $request->input('password'));

        if (!$user) {
            return $this->getResponseData('0', 'Authentication Fail', [
                        'AuthorizationHeader' => $request->header('authorization'),
                        'serverAuthType' => 'Basic Auth username/password',
                            ]
            );
        }
        if (!$user->status) {
            return $this->getResponseData('0', 'Sorry! Your user account has been deactivated', $user
            );
        }
        
        
        $user->api_token = base64_encode($request->input('username') . ':' . $request->input('password'));
        //===================update player id for active use ====================================
        if ($user->userol_id > 1) {
            $user->playerId = $request->header('player-id');
            $user->active = 1;
        }
        //=======================================================================================
        
        $user->save();
        return $this->getResponseData('1', trans('messages.userAuthenticated'), $user->makeVisible('api_token'));
    }

    /**
     * compare username and password againt the database
     * @param $username, $password
     * @return User Model | false
     */
    private function authenticating($username, $password) {

        $user = Users::where('email', $username)->first();

        if ($user AND app('hash')->check($password, $user->password)) {
            return $user;
        } else {
            return false;
        }
    }

}
