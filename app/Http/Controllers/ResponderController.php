<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;

class ResponderController extends Controller
{
    protected $property_photos_path = __DIR__.'/../../../public/uploads/property_images/';
    protected $property_plan_path = __DIR__.'/../../../public/uploads/property_plan_images/';

    /**
     * Check result true or fails request and return the message.
     * @param  array  $Object​​ query request result
     * @param  string $objectName name of object to print out the message
     * @return string massage about query  and massage from the request
     */
    public function returnResult($object)
    {
        if ($object) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Generate message back to the request object.
     * @param int $setStatus True/False about transaction result;
     * @param string $ObjectIndex index of Object name to print in message
     * @param int $setStatusMessage index of prepare message to print out
     * @retune array Array result with sample message about transaction info
     */
    public function returnMessage($setStatus, $ObjectIndex = null, $setStatusMessage = null, $respond = '', $customMessage = null)
    {
//        ================Prepare message for mulity leanguege ==============================
        $ObjectList = ['User' => 'User', 'Agent' => 'Agent', 'Property' => 'Property', '6'=>'',
            'Age' => 'Agent', 'Residence' => 'Residence', 'Rating' => 'Rating', ];
        $list_message = [
            1 => 'Not Updated..!!',
            2 => 'Successfully Updated..!!',
            3 => 'Succefully Delete..!',
            4 => "Sorry, System doesn't recognize your input.",
            5 => 'Not found..!!',
            6 => trans('messages.data_validation'), //"Data validation failded."
            7 => trans('messages.fileUploadFailed'),
        ];
//        ======================================================================================

        $message = '';
        if ($ObjectIndex != null):
            $message = $ObjectList[$ObjectIndex].' '.$list_message[$setStatusMessage]; elseif ($message != null):
            $message = $list_message[$setStatusMessage];
        endif;
        if ($customMessage != null):
            $message = $customMessage;
        endif;
        $return_result = ['status' => $setStatus, 'message' => $message, 'respond' => $respond];

        return $return_result;
    }

    /**
     * Get a validate for each input with customize message.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function formValidater($data, $rules = null)
    {
        if ($rules == null):
            $rules = $this->setUserValidation();
        endif;
        $validator = Validator::make($data->all(), $rules, $this->setUserValidationMessage());
        if ($validator->fails()) {
            $errors = $validator->errors();

            return $errors->first();
        }
    }

    /**
     * Get a validate for each input with customize message.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function formAgentValidater($data)
    {
        $validator = Validator::make($data->all(), $this->setAgentValidation($data), $this->setUserValidationMessage());
        if ($validator->fails()) {
            $errors = $validator->errors();

            return $errors->first();
        }
    }

    /**
     * Get a validate for each input with customize message.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function formPropertyValidater($data)
    {
        $validator = Validator::make($data->all(), $this->setPropertyValidation($data), $this->setUserValidationMessage());
        if ($validator->fails()) {
            $errors = $validator->errors();

            return $errors->first();
        }
    }

    /**
     * Get a validate for each input with customize message.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function updateValidater($data, $getUser)
    {
        $validator = Validator::make($data->all(), $this->setUpdateValidation($data, $getUser), $this->setUserValidationMessage());
        if ($validator->fails()) {
            $errors = $validator->errors();

            return $errors->first();
        }
    }

    /**
     * Get a validate for each input with customize message.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function updatePropertyValidater($data, $getUser)
    {
        $validator = Validator::make($data->all(), $this->setUpdatePropertyValidation($data, $getUser), $this->setUserValidationMessage());
        if ($validator->fails()) {
            $errors = $validator->errors();

            return $errors->first();
        }
    }

    /**
     * List of field and rule for use to validate the user input.
     * @return array
     */
    public function setUserValidation($data = null)
    {
        $rules = [
            'first_name' => 'max:50|string',
            'last_name' => 'max:50|string',
            'username' => 'required|max:20|min:5|alpha_num',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', //Contain at least one uppercase/lowercase letters and one number
            'retype' => 'required|same:password',
            'userol_id' => 'required',
            'email' => 'required|email|unique:users',
            'dob' => 'date|date_format:Y-m-d',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3200.896', //it's size is smaller or equal to 32.896 kb'
            'county_code' => 'max:50',
        ];

        return $rules;
    }

    /**
     * List of field and rule for use to validate the user input.
     * @return array
     */
    public function setUpdateValidation($data = null, $getUser = null)
    {
        $rules = [
            'username' => 'required|unique:users,username,'.$getUser['id'].',id|max:20|min:5',
            'email' => 'unique:users,email,'.$getUser['id'].',id',
            'first_name' => 'max:50|string',
            'last_name' => 'max:50|string',
            'dob' => 'date|date_format:Y-m-d',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3200.896', //it's size is smaller or equal to 32.896 kb'
            'county_code' => 'max:50',
        ];
        if ($getUser['userol_id'] == 3):// Agent user\
            $rules['company_name'] = 'required';
        $rules['company_number'] = 'required';
        $rules['company_address'] = 'required';
        $rules['company_licence'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:3200.896';
//            return $this->fileValidation($rules, $data, 'licence');
        endif;

        return $rules;
    }

    /**
     * Validate a specific object attribute.
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function setPropertyValidation($data)
    {
        $rules = [
            'pro_title' => 'required|min:5',
            'pro_price' => 'required|numeric|min:10',
//            'pro_use_id' => 'required',
            'pro_lng' => 'required|numeric',
            'pro_lat' => 'required|numeric',
            'pro_residence' => 'required',
            'pro_search_type' => 'required',
            'pro_amenities' => 'required',
            'pro_detail' => 'required|max:500',
//            'pro_contact_name' => 'required|alpha_num|max:50',
//            'pro_contact_number' => 'required|alpha_num|max:50',
//            'pro_contact_email' => 'required|email',
            'pro_city' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_state' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_zip' => 'required|alpha_num',
            'pro_address' => 'required',
            'pro_age' => 'required|numeric|max:100',
        ];
        if ($data->file('photos')):
            $rules = $this->fileValidation($rules, $data, 'photos');
        endif;
        if ($data->file('plan')):
            $rules = $this->fileValidation($rules, $data, 'plan');
        endif;

        return $rules;
    }

    /**
     * List of field and rule for use to validate the user input.
     * @return array
     */
    public function setAgentValidation($data = null)
    {
        $rules = [
            'first_name' => 'max:50|string',
            'last_name' => 'max:50|string',
            'username' => 'required|unique:users|max:20',
            'userol_id' => 'required',
            'email' => 'required|email|unique:users',
            'company_name' => 'required',
            'company_number' => 'required',
            'company_address' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3200.896', //it's size is smaller or equal to 32.896 kb'
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', //Contain at least one uppercase/lowercase letters and one number
            'retype' => 'required|same:password',
            'company_licence' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3200.896', ]; //it's size is smaller or equal to 32.896 kb'

        return $rules;
    }

    /**
     * List error message support multi language.
     * @return array list of error message for each input
     */
    public function setUserValidationMessage()
    {
        $errorMessage = [
            'username.required' => 'A user name is required',
            'username.min' => 'The username must be at least 5 characters',
            'password.required' => 'A user password is required',
            'property_id.exists' => 'Property id not found',
        ];

        return $errorMessage;
    }

    /**
     * Function to check multi file upload.
     * @param  array $staticRules list input to validate
     * @param  array $data requested data
     * @param  string $strFileName input name that concent the file
     * @return array List of file name from generate number and time
     */
    public function fileValidation($staticRules, $data, $strFileName)
    {
        $nbr = count($data->file($strFileName)) - 1;

        foreach (range(0, $nbr) as $index):
            $staticRules[$strFileName.'.'.$index] = 'required|image|mimes:jpeg,png,jpg,gif,svg'; //it's size is smaller or equal to 5120 kb
        endforeach;

        return $staticRules;
    }

    /**
     * Multi Files Upload.
     * @param  array $request input data
     * @param  string $inputName input data
     * @param  Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return array  List of generated file name have been uploaded
     */
    public function doFileUpload($request = null, $inputName = null)
    {
        $files = $request->file($inputName);
        $arrayName = [];
        try {
            foreach ($files as $file):
                if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                    throw new \Exception($file->getClientSize());
                }
            $fileName = $this->generateFileName($file);
            $file->move($this->property_photos_path, $fileName);
            array_push($arrayName, $fileName);
            endforeach;

            return $arrayName;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function doFileUpload001($request = null, $inputName = null, $path = null)
    {
        $files = $request->file($inputName);

        $filePath = 'uploads';
        if ($path != null):
            $filePath = $path;
        endif;
        $arrayName = [];
        $i = 0;
        if (! empty($files)):
            foreach ($files as $file):
                $fileName = $this->generateFileName($file->guessExtension());
        $file->move($filePath, $fileName);
        array_push($arrayName, $fileName);
        $i++;
        endforeach; else:
            $fileName = url('/').'/uploads/semplePhoto.png';
        array_push($arrayName, $fileName);
        endif;
//        return implode(",", $arrayName);
//        dd($arrayName);
        return $arrayName;
    }

    /**
     * Remove Multi Files have uploaded from the server.
     * @param  array $object object get from database
     * @param array $fileList list of fields to remove files
     * @param array $request list of fields to update
     * @return bolean describe file removing
     */
    public function removeFileUpload($object, $fileList, $request)
    {
        $directory = url('/').'/';
//        ==========List file name from property given============
        foreach ($fileList as $key): //check all key point to check file directory
            if ($request->file($key)):
                $getFileNames = json_decode($object['pro_'.$key]); // change json recode to array
                foreach ($getFileNames as $value): // go through the recode value to get the file url and name
                    $fileName = str_replace($directory, '', $value); //remove the directory, keep only the file name
                    if ($fileName != 'samplePhoto.png'):// keep file sample, get only property have upload photo/plan
                        if (file_exists($fileName)) { // unlink or remove previous image from folder
                            unlink($fileName);
                        }
        endif;
        endforeach;
        endif;
        endforeach;
    }

    /**
     * File Upload.
     * @param  array $request input data
     * @param  string $inputName input data
     * @param  string $path custom folder upload file
     * @return array  List of generated file name have been uploaded
     */
    public function doPhotoUpload($request = null, $inputName = null, $path = null)
    {
        $file = $request->file($inputName);
        $filePath = 'uploads';
        if ($path != null):
            $filePath = $path;
        endif;
        if (! empty($file)):
            $fileName = $this->generateFileName($file->guessExtension());
//        $fileName= 'air-conditioning.png';
        $file->move($filePath, $fileName);
        endif;

        return $fileName;
    }

    public function clearDirectory()
    {
        $files = glob('uploads/*');
        //Loop through the file list.
        foreach ($files as $file) {
            //Make sure that this is a file and not a directory.
            if (is_file($file)) {
                //Use the unlink function to delete the file.
                unlink($file);
            }
        }
        dd('File removed..!');
    }

    /**
     * Generate file name  as random with date time.
     * @param  string $extension file extension
     * @return ​string new file name with time and random number from 5-10000
     */
    public function generateFileName($file)
    {
        //building the file name
        $fileName = hash('sha1', rand(5, 10000).time());
        $fullFileName = '';
        if (! is_null($file->guessExtension())) {
            $fullFileName = $fileName.'.'.$file->guessExtension();
        } else {
            $fullFileName = $fileName.'.jpeg';
        }

        return $fullFileName;
    }

    public function generateFileName001($extension = null)
    {
        $filePath = url('/').'/uploads/';

        return $filePath.rand(5, 10000).time().'.'.$extension;
    }
}
