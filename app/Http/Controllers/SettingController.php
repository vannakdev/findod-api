<?php

namespace App\Http\Controllers;

use Validator;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\ResponderController;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DB;
class SettingController extends Controller
{
    protected $video_image = __DIR__ . '/../../../public/uploads/video_promotion_image/';
	public function __construct() {
        
    }
    public function show()
    {
        $getProjectName= DB::table('residence_setting')
            ->join('residences', 'residence_setting.residence_id', '=', 'residences.id')
            ->join('property_type','residence_setting.property_type_id','property_type.id')
            ->join('property_type_translations', 'property_type_translations.property_type_id', 'property_type.id')
            ->join('residence_translates','residence_translates.residence_id','residences.id')
            ->select('residence_setting.residence_id', 'residence_setting.property_type_id','residence_translates.res_title', 'residence_setting.status', 'property_type_translations.title', 'residence_translates.locale', 'property_type.id')
            ->where('property_type_translations.locale','=', app('translator')->getLocale())
            ->where('residence_translates.locale','=', app('translator')->getLocale())
            ->where('residence_setting.status','=', 1)
            ->get();

        return $this->getResponseData('1', "Success", $getProjectName);
    }
    public function get($key)
    {
        // if (!Auth::user()->isAdmin()) {
        //     return $this->getResponseData("0", "Unauthourized Access", 'Your are not allowed to access this resource');
        // }

        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return $this->getResponseData("0", "Resourece not found", 'The resource which you are trying to process is not found.');
        }

        return $this->getResponseData("1", "Success", $setting);
    }

    public function getByKeys(Request $request)
    {
        if (! $request->input('keys')) {
            return $this->getResponseData("0", "Invalid Arguments", 'Request parameter [keys] is required.');
        }

        $key_array = explode(',', $request->input('keys'));

        if (!is_array($key_array)) {
            return $this->getResponseData("0", "Invalid Arguments", 'Request parameter [keys] is not array');
        }

        $settings = Setting::whereIn('key', $key_array)->get([ 'key','value' ])->toArray();

        $keys = array_pluck($settings, 'key');

        if (count($key_array) != count($keys)) {
            return $this->getResponseData("0", "Invalid Key", 'There are some invalid keys you have provided. Please check with Admin Developer');
        }

        $values = array_pluck($settings, 'value');
        $settings =array_combine($keys, $values);
        return $this->getResponseData("1", "Success", $settings);
    }

    public function update(Request $request, $key)
    {
        if (!Auth::user()->isAdmin()) {
            return $this->getResponseData("0", "Unauthourized Access", 'Your are not allowed to access this resource');
        }

        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return $this->getResponseData("0", "Resourece not found", 'The setting you trying to process is not found.');
        }

        $validator = Validator::make($request->all(), [
                                        'value' => 'required'
                                    ]);

        if ($validator->fails()) {
            return $this->getResponseData("0", "Data validation failed.", $validator->errors()->first());
        }

        $setting->value = $request->input('value');

        if ($setting->save()) {
            return $this->getResponseData("1", "Success", "Setting has been saved successfully");
        } else {
            return $this->getResponseData("0", "Internal Server Error", "There are some problem while trying to process your request.");
        }
    }

    public function uploadvideoImage(Request $request) {
        $responder = new ResponderController;
        $validator = Validator::make($request->all(), [
                    'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120']
        );
        if ($validator->fails()):
            return $this->getResponseData('0', trans('messages.fileUploadFailed'), $validator->errors()->first());
        endif;

        $doUploadFile = $this->backendUpdateFile($request->file('photo'), $this->video_image);
        if (!$doUploadFile) {
            return $this->getResponseData('0', trans('messages.fileUploadFailed'), $doUploadFile);
        }
        return $responder->getResponseData('1', 'File upload succefully.', $doUploadFile);
    }
	
	/**
     * Upload promotion video avatar and save to user object with video promotion->avatar
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file , [ReferenceType] User Model Object;
     * @return True on success|Exception
     */
    private function backendUpdateFile($file, $path) {
        try {

            if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                throw new \Exception($file->getClientSize());
            }

            //building the file name
            $fullFileName = PropertyController::generateFileName($file);

            //upload file the path 
            //please specify the protected $user_avatar_directory in this Controller in the top
            $file->move($path, $fullFileName);

            /*  Update to Settings Table */

            $imageURL = env('APP_URL').'uploads/video_promotion_image/'.$fullFileName;
            $setting = Setting::where('key', 'landing_page_video_image')->update(['value' => $imageURL]); 

            return $fullFileName;
        } catch (\Exception $e) {

            throw new \Exception($e->getMessage());
        }
    }
}
