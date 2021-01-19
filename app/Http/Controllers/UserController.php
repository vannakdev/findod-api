<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Http\Controllers\ResponderController;
use App\Users;
//use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;

/**
 * Description of newPHPClass.
 *
 * @author OU Sophea : ODIC
 */
class UserController extends Controller
{
    protected $company_license_directory = __DIR__.'/../../../public/uploads/company_licence/';
    protected $profile_image = __DIR__.'/../../../public/uploads/profile_image/';
    protected static $simple_user_role_id = 2;
    protected static $agent_role_id = 3;

    public function __construct()
    {
    }

    public function userRegister(Request $request)
    {
        dd('test');
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:50|string',
                    'last_name' => 'required|max:50|string',
                    'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                    'retype' => 'required|same:password',
                    'email' => 'required|email|unique:users',
            ]
        );
        if ($validator->fails()) {
            return $this->getResponseData('0', $validator->errors()->first(), '');
        }
        if (! $request->header('player-id')) {
            return $this->getResponseData('0', trans('messages.player_requied'), '');
        }
        //dd($request->input('setting'));
        //create user object and assign value from request's data
        $user = new Users();

        $user->email = $request->input('email');
        $user->password = app('hash')->make($request->input('password'));
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->playerId = $request->header('player-id');
        $user->userol_id = self::$simple_user_role_id;
        //$user->setting = $request->input('setting');

        $gps = $request->input('setting:gps');
        $language = $request->input('setting:language');
        $location = $request->input('setting:location');
        $notification = $request->input('setting:notification');
        $setSetting = [
            'gps' => $gps,
            'language' => $language,
            'location' => $location,
            'notification' => $notification, ];
        $user->setting = json_encode($setSetting);

        //commit save user into the database.
        $user->save();

        return $this->getResponseData('1', trans('messages.user_create_successfull'), $user->fresh());
    }

    public function agentRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:50|string',
                    'last_name' => 'required|max:50|string',
                    'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                    'retype' => 'required|same:password',
                    'email' => 'required|email|unique:users',
                    'company_name' => 'required|max:50',
                    'company_number' => 'required|max:50',
                    'company_address' => 'required|max:50',
                    'company_licence' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                        ]
        );

        if ($validator->fails()) {
            return $this->getResponseData('0', $validator->errors()->first(), '');
        }
        if (! $request->header('player-id')) {
            return $this->getResponseData('0', trans('messages.player_requied'), '');
        }

        //create user object and assign value from request's data
        $agent = new Users();
        //attribute same as simple user
        $agent->email = $request->input('email');
        $agent->password = app('hash')->make($request->input('password'));
        $agent->first_name = $request->input('first_name');
        $agent->last_name = $request->input('last_name');
        $agent->userol_id = self::$agent_role_id;
        $agent->playerId = $request->header('player-id');
        //addtional attribute for agent
        $agent->company_name = $request->input('company_name');
        $agent->company_number = $request->input('company_number');
        $agent->company_address = $request->input('company_address');
        //$agent->setting = $request->input('setting');
        $gps = $request->input('setting:gps');
        $language = $request->input('setting:language');
        $location = $request->input('setting:location');
        $notification = $request->input('setting:notification');
        $setSetting = [
            'gps' => $gps,
            'language' => $language,
            'location' => $location,
            'notification' => $notification, ];
        $agent->setting = json_encode($setSetting);

        //Check and Upload user's avatar
        if ($request->hasFile('company_licence')) {
            try {
                //getting uploaded file , \Illuminate\Http\UploadedFile $file.
                $file = $request->file('company_licence');
                //invoke for private function to handle user profile upload
                $this->uploadCompanyLicense($file, $agent);
            } catch (\Exception $e) {
                return $this->getResponseData('0', trans('messages.fileUploadFailed'), $e->getMessage());
            }
        }

        //commit save agent into the database.
        $agent->save();

        return $this->getResponseData('1', trans('messages.user_create_successfull'), $agent->fresh());
    }

//    public function update($id, Request $request) {
//        $get_user = Users::find($id);
//        if ($get_user != NULL) { // check data before update the recorde
//            $get_user->update($request->all());
//            $get_user = 'Update successfully';
//        } else {
//            $get_user = 'Bad input, data can not update';
//        }
//        return response()->json($get_user, 200);
//    }

    /**
     * @param Request $request
     * @return type
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:50|string',
                    'last_name' => 'required|max:50|string',
                    'gender' => 'in:male,female',
                    'dob' => 'date',
                    'phone' => 'max:50',
                    'country_code' => 'max:5',
                    'company_name' => 'max:50',
                    'company_number' => 'max:50',
                    'company_address' => 'max:50',
                    'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                    'company_licence' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                        ]
        );

        if ($validator->fails()) {
            return $this->getResponseData('0', $validator->errors()->first(), '');
        }
        $data = $request->input();

        $gps = $data['setting:gps'];
        $language = $data['setting:language'];
        $location = $data['setting:location'];
        $notification = $data['setting:notification'];
        $setSetting = [
            'gps' => $gps,
            'language' => $language,
            'location' => $location,
            'notification' => $notification, ];
        $data['setting'] = json_encode($setSetting);

        $user = Auth::user();
        $user->fill($data);

        //Check and Upload user's avatar
        if ($request->hasFile('company_licence')) {
            try {
                //getting uploaded file , \Illuminate\Http\UploadedFile $file.
                $file = $request->file('company_licence');
                //invoke for private function to handle user profile upload
                $this->uploadCompanyLicense($file, $user);
            } catch (\Exception $e) {
                return $this->getResponseData('0', trans('messages.fileUploadFailed'), $e->getMessage());
            }
        }

        //Check and Upload user's avatar
        if ($request->hasFile('photo')) {
            try {
                //getting uploaded file , \Illuminate\Http\UploadedFile $file.
                $file = $request->file('photo');
                //invoke for private function to handle user profile upload
                $this->uploadProfileImage($file, $user);
            } catch (\Exception $e) {
                return $this->getResponseData('0', trans('messages.fileUploadFailed'), $e->getMessage());
            }
        }
        if ($request->has('setting')) :
            $user->setting = $request->input('setting');
        endif;

        //commit save agent into the database.
        $user->save();

        return $this->getResponseData('1', trans('messages.user_updated'), $user->fresh());
    }

    public function backendUploadUserLicence(Request $request)
    {
        $responder = new ResponderController;
        $validator = Validator::make($request->all(), [
                    'company_licence' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', ]
        );
        if ($validator->fails()) :
            return $this->getResponseData('0', trans('messages.fileUploadFailed'), $validator->errors()->first());
        endif;

        $doUploadFile = $this->backendUpdateFile($request->file('company_licence'), $this->company_license_directory);
        if (! $doUploadFile) {
            return $this->getResponseData('0', trans('messages.fileUploadFailed'), $doUploadFile);
        }

        return $responder->getResponseData('1', 'File upload succefully.', $doUploadFile);
    }

    public function backendUploadUserPhoto(Request $request)
    {
        $responder = new ResponderController;
        $validator = Validator::make($request->all(), [
                    'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', ]
        );
        if ($validator->fails()) :
            return $this->getResponseData('0', trans('messages.fileUploadFailed'), $validator->errors()->first());
        endif;

        $doUploadFile = $this->backendUpdateFile($request->file('photo'), $this->profile_image);
        if (! $doUploadFile) {
            return $this->getResponseData('0', trans('messages.fileUploadFailed'), $doUploadFile);
        }

        return $responder->getResponseData('1', 'File upload succefully.', $doUploadFile);
    }

    /**
     * Upload user's avatar and save to user object with profile->avatar.
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file , [ReferenceType] User Model Object;
     * @return true on success|Exception
     */
    private function backendUpdateFile($file, $path)
    {
        try {
            if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                throw new \Exception($file->getClientSize());
            }

            //building the file name
            $fullFileName = PropertyController::generateFileName($file);

            //upload file the path
            //please specify the protected $user_avatar_directory in this Controller in the top
            $file->move($path, $fullFileName);

            return $fullFileName;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function sendResetEmail(Request $request)
    {
        $user = Users::where('email', $request->input('email'))->first();
        $token = '';

        if (! $user) {
            return $this->getResponseData('0', trans('messages.userNotFound'), $request->input('email'));
        }

        $token = PasswordResetController::generateResetPasswordToken($user->email);

        if (! $token) {
            return $this->getResponseData('0', trans('messages.resetPassword'), $user->email);
        }

        try {
            $name = ucfirst($user->first_name).' '.ucfirst($user->last_name);
            PasswordResetController::sendResetPasswordEmail($user->email, $token, $name);
        } catch (\Exception $e) {
            return $this->getResponseData('0', $e->getMessage(), $user->email);
        }

        return $this->getResponseData('1', trans('messages.resetEmailCheck'), $token);
    }

    public function resetPassword(Request $request)
    {
        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        if (! PasswordResetController::verify_token($email, $token)) {
            return $this->getResponseData('0', trans('messages.resetPassword'), ['email' => $email, 'token' => $token]);
        }

        $validator = Validator::make($request->all(), [
                    'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);

        if ($validator->fails()) {
            return $this->getResponseData('0', trans('messages.data_validation').json_decode(json_encode($validator->errors()->all(), JSON_FORCE_OBJECT)), '');
        }

        if (! PasswordResetController::change_password($email, $password)) {
            return $this->getResponseData('0', trans('messages.resetPassword'), '');
        }

        $user = Users::where('email', $email)->first();
        PasswordResetController::delete_all_token($user->email);

        //=========================Sending notification===============================
        $notify = new NotificationController();
        $notify->notifyPasswordChangeConfirm($user);
//        ======================================================================
        return $this->getResponseData('1', trans('messages.resetPasswordSuccess'), $user);
    }

    /**
     * @param Request $request
     * @param Token $token
     * @return type
     */
    public function resetLinkAuth(Request $request, $token)
    {
        $validator = Validator::make($request->query(), [
                    'email' => 'required|email|exists:users,email,active,1',
        ]);
        if ($validator->fails()) {
            return $this->getResponseData('0', trans('messages.data_validation').$validator->errors()->first(), '');
        }
        if (! PasswordResetController::verify_token($request->query('email'), $token)) {
            return $this->getResponseData('0', trans('messages.resetPassword'), ['email' => $request->query('email'), 'token' => $token]);
        }

        return $this->getResponseData('1', trans('messages.resetPasswordByWeb'), '');
    }

    public function sendLinkResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|email|exists:users,email,active,1',
        ]);

        if ($validator->fails()) {
            return $this->getResponseData('0', $validator->errors()->first(), '');
        }

        $link = PasswordResetController::generateResetPasswordLink($request->input('email'));

        if (! $link) {
            return $this->getResponseData('0', trans('messages.resetPassword'), $request->input('email'));
        }

        try {
            $user = Users::where('email', $request->input('email'))->first();
            $name = ucfirst($user->first_name).' '.ucfirst($user->last_name);
            PasswordResetController::sendLinkResetPasswordEmail($request->input('email'), $link, $name);
        } catch (\Exception $e) {
            return $this->getResponseData('0', $e->getMessage(), $request->input('email'));
        }

        return $this->getResponseData('1', trans('messages.resetEmailCheck'), $link);
    }

    /**
     * Upload user's avatar and save to user object with profile->avatar.
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file , [ReferenceType] User Model Object;
     * @return true on success|Exception
     */
    private function uploadCompanyLicense($file, &$agent)
    {
        try {
            if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                throw new \Exception($file->getClientSize());
            }

            //building the file name
            $fileName = base64_encode(microtime()).$file->getClientOriginalName();
            $fullFileName = '';

            if (! is_null($file->guessExtension())) {
                $fullFileName = $fileName.'.'.$file->guessExtension();
            } else {
                $fullFileName = $fileName.'.jpeg';
            }

            //upload file the path
            //please specify the protected $user_avatar_directory in this Controller in the top
            $file->move($this->company_license_directory, $fullFileName);

            //attach the $fullFileName to User object.
            $agent->company_licence = $fullFileName;

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Upload user's avatar and save to user object with profile->avatar.
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file , [ReferenceType] User Model Object;
     * @return true on success|Exception
     */
    private function uploadProfileImage($file, &$user)
    {
        try {
            $path = $this->profile_image;
            if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                throw new \Exception($file->getClientSize());
            }

            //building the file name
            $fileName = str_replace(' ', '', base64_encode(microtime()).$file->getClientOriginalName());
            $fullFileName = '';

            if (! is_null($file->guessExtension())) {
                $fullFileName = $fileName;
            } else {
                $fullFileName = $fileName.'.jpeg';
            }

            //upload file the path
            //please specify the protected $user_avatar_directory in this Controller in the top
            $this->uploadResizeProfileImage($file->getRealPath(), $fullFileName, $path);

            //attach the $fullFileName to User object.
            $user->photo = $fullFileName;

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function uploadProfileImageByUrl($url, &$user)
    {
        try {
            $path = $this->profile_image;
            $file = pathinfo($url);
            $fileContentInfo = get_headers($url, true);

            if (! isset($fileContentInfo['Content-Length'])) :
                return $this->getResponseData('0', trans('messages.fileUploadFailed'), '');
            endif;

//            $fileSize = $fileContentInfo['Content-Length'];

            $fileExtension = (isset($file['extension'])) ? $file['extension'] : 'jpeg';
//            =========
//           sample image from facebook: http://graph.facebook.com/67563683055/picture?type=normal
//            ======================================================================================
            //building the file name
            $fileName = base64_encode(microtime()).'_findod_property.'.$fileExtension;

            //upload file the path
            //please specify the protected $user_avatar_directory in this Controller in the top
            $this->uploadResizeProfileImage($url, $fileName, $path);

            //attach the $fullFileName to User object.
            $user->photo = $fileName;

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function uploadResizeProfileImage($file, $fileName, $path)
    {
        $imgResize = Image::make($file);
        // resize the image to a height of 500 and constrain aspect ratio (auto width)
        $imgResize->resize(null, 500, function ($constraint) {
            $constraint->aspectRatio();
        });
        $imgResize->save($path.'/'.$fileName);
    }

    /**
     * Get more information related with the user.
     *
     * @return array Number of favorite by user and number of hosting property
     */
    public function userProfile()
    {
        $returnResult = [];
        $user = Auth::user();
        $user = $this->countFavorite();
        $returnResult['properties_count'] = $user['properties_count'];
        $returnResult['favorites_count'] = $user['favorites_count'];

        return $this->getResponseData('1', '', $returnResult);
    }

    public function countFavorite()
    {
        $user = Auth::user();
        $query = $user->newQuery();
        $query->withCount('properties', 'favorites');
        $query->where('users.id', $user->id);

        return $query->first()->toArray();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that.
     *
     * @return Response
     */
    public function socialLogin(Request $request)
    {
        $rules = [
            'first_name' => 'required|max:50|string',
            'last_name' => 'required|max:50|string',
            'email' => 'required|email',
            'provider_id' => 'required|exists:social,id',
        ];
        if ($request->has('photo')) :
            $rules['photo'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120'; else :
            $rules['photoUrl'] = 'required|active_url';
        endif;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->getResponseData('0', trans('messages.data_validation').$validator->errors()->first(), '');
        }

        if (! $request->header('player-id')) {
            return $this->getResponseData('0', trans('messages.requiredPlayerId'), '');
        }

        $generatePassword = base64_encode($request->input('email').':'.str_random(40));
        $getUser = Users::where('email', $request->input('email'))->first();

        if ($getUser != null) { // Request email exist in database

            if ($getUser->status == 0) {
                return $this->getResponseData('0', 'Sorry! Your user account has been deactivated', $getUser
                );
            } else {
                $getUser->api_token = base64_encode($request->input('email').':'.str_random(40));
                // reset password for new login with social account login
                $getUser->password = app('hash')->make($generatePassword);
                $getUser->playerId = $request->header('player-id');
                $getUser->save();
                $respondUser = $getUser->makeVisible('password');
                $getUser->password = $generatePassword;

                return $this->getResponseData('1', trans('messages.userAuthenticated'), $getUser);
            }
        }

        //create new user object and assign value from request's data
        $user = new Users();
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->provider_id = $request->input('provider_id');
        $user->playerId = $request->header('player-id');
        $user->userol_id = self::$simple_user_role_id;
        $user->password = app('hash')->make($generatePassword);
        $user->setting = '';
        //commit save user into the database.
        //Check and Upload user's avatar
        if ($request->hasFile('photo')) {
            try {
                //getting uploaded file , \Illuminate\Http\UploadedFile $file.
                $file = $request->file('photo');
                //invoke for private function to handle user profile upload
                $this->uploadProfileImage($file, $user);
            } catch (\Exception $e) {
                return $this->getResponseData('0', trans('messages.fileUploadFailed'), $e->getMessage());
            }
        }
        if ($request->has('photoUrl')) {
            try {
                //getting uploaded file , \Illuminate\Http\UploadedFile $file.
                $photoUrl = $request->input('photoUrl');
                //invoke for private function to handle user profile upload
                $this->uploadProfileImageByUrl($photoUrl, $user);
            } catch (\Exception $e) {
                return $this->getResponseData('0', trans('messages.fileUploadFailed'), $e->getMessage());
            }
        }

        if ($user->save()) {
            $respondUser = $user->fresh();
            $respondUser->makeVisible('password');
            $respondUser->password = $generatePassword;

            return $this->getResponseData('1', trans('messages.user_create_successfull'), $respondUser);
        } else {
            return $this->getResponseData('0', trans('messages.user_create_unsuccessfull'), $user->fresh());
        }
    }

    public function deactivate()
    {
        $user = Auth::user();
        $getUser = Users::where('email', $user->email)
                ->where('status', 1)
                ->update(['status' => 0]); //  0=>  user are deactivated

        $notify = new NotificationController();

        $notify->notifyDeactivateAccount($user->email);
        //NotificationController::notifyDeactivateAccount($user->email);

        return $this->getResponseData('1', trans('user_deactivated'), $getUser);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('email', $user->email)->first();
        if ($authUser) {
            return $authUser;
        }

        return User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'provider' => $provider,
                    'provider_id' => $user->id,
        ]);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
//                    'current' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // Not need as we use the author when login
                    'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                    'retype' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->getResponseData('0', trans('messages.data_validation').$validator->errors()->first(), '');
        }

        $user->password = app('hash')->make($request->password);

        if (! $user->save()) {
            return $this->getResponseData('0', trans('messages.resetPasswordFailed'), $user);
        }

        return $this->getResponseData('1', trans('messages.resetPasswordSuccess'), $user->fresh());
    }

    public function checkAgentValidationg($data)
    {
        $rules = [
            'first_name' => 'required|max:50|string',
            'last_name' => 'required|max:50|string',
            'userol_id' => 'required',
            'email' => 'email|unique:users',
            'company_name' => 'required',
            'company_number' => 'required',
            'company_address' => 'required',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', //Contain at least one uppercase/lowercase letters and one number
            'retype' => 'required|same:password', ];
        $nbr = count($data->file('licence')) - 1;
        foreach (range(0, $nbr) as $index) :
            $rules['licence.'.$index] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3200.896'; //it's size is smaller or equal to 32.896 kb
        endforeach;

        return $this->validate($data, $rules);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->active = 0;
        if (! $user->save()) :
            return $this->getResponseData('0', trans('messages.logoutFailed'), $user->fresh());
        endif;

        return $this->getResponseData('1', trans('messages.logoutSuccess'), $user->fresh());
    }

    //===============For development enviroment only======================
    public function showAllUsers()
    {
        return response(Users::all());
    }
}
