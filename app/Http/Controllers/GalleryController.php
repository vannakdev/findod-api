<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\GalleryDetail;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Validator;

class GalleryController extends Controller
{
    protected static $gallery_upload_directory = 'uploads/gallery/';

    public function createFromRequest(Request $request)
    {
        $messages = $this->getFileValidationCustomErrorMessages();

        $validator = Validator::make($request->all(), [
                                        'files.*' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                                        'key'     => 'max:191',
                                        'title'   => 'required|max:191',
                                    ], $messages);

        if ($validator->fails()) {
            return $this->getResponseData('0', 'Data validation failed.', $validator->errors()->first());
        }

        $gallery = Gallery::where('key', $request->input('key'))->first();
        try {
            if ($gallery) {
                $uploaded_files = self::update($request->input('key'), $request->input('title'), $request->file('files'));
            } else {
                $uploaded_files = self::create($request->input('key'), $request->input('title'), $request->file('files'));
            }
        } catch (Exception $e) {
            return $this->getResponseData('0', 'Internal Server Error', $validator->errors()->first());
        }

        return $this->getResponseData('1', 'Success', is_null($uploaded_files) ? '' : $uploaded_files);
    }

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
                                        'id' => 'required_without_all:key|integer',
                                        'key'     => 'required_without_all:id|max:191',
                                    ]);

        if ($validator->fails()) {
            return $this->getResponseData('0', 'Data validation failed.', $validator->errors()->first());
        }

        $gallery = Gallery::query();

        if ($request->input('id')) {
            $gallery = $gallery->find($request->input('id'));
            if (! $gallery) {
                return $this->getResponseData('0', 'No Result Found', 0);
            }
        }

        if ($request->input('key')) {
            $gallery = $gallery->where('key', $request->input('key'))->first();
            if (! $gallery) {
                return $this->getResponseData('0', 'No Result Found', 0);
            }
        }

        return $this->getResponseData('1', 'Success', $gallery->with('images')->first());
    }

    public function deleteItem($id)
    {
        $gallery_detail = GalleryDetail::find($id);

        if (! $gallery_detail) {
            return $this->getResponseData('0', 'Data validation failed.', 'Image is not found!');
        }

        $gallery_detail->delete();

        return $this->getResponseData('1', 'success', '');
    }

    public function updateItemOrder(Request $request, $id)
    {
        $gallery_detail = GalleryDetail::find($id);

        if (! $gallery_detail) {
            return $this->getResponseData('0', 'Data validation failed.', 'Image is not found!');
        }

        $validator = Validator::make($request->all(), [
                                        'order' => 'required|integer',
                                    ]);

        if ($validator->fails()) {
            return $this->getResponseData('0', 'Data validation failed.', $validator->errors()->first());
        }

        $gallery_detail->order = $request->input('order');
        $gallery_detail->save();

        return $this->getResponseData('1', 'Success', 'Image Update successfully');
    }

    public static function create($key, $title, array $files)
    {

        // working with file upload first
        $file_array = [];
        foreach ($files as $file) {
            $file_array[] = self::uploadFile($file);
        }

        //working with the database
        if (is_null($key)) {
            $key = str_slug($title, '_');
        }

        $gallery = Gallery::create([
            'user_id' => Auth::id(),
            'key' => $key,
            'title' => $title,
        ]);

        for ($i = 0; $i < count($file_array); $i++) {
            $gallery->images()->create([
                'origin_filename' => $file_array[$i]['original_file_name'],
                'filename' => $file_array[$i]['filename'],
                'order' => $i,
                'file_path' =>  $file_array[$i]['path'],
            ]);
        }

        return $gallery->images();
    }

    public static function update($key, $title, array $files = null)
    {
        if (is_null($files)) {
            return null;
        }
        //working with the database
        $gallery = Gallery::where('key', $key)->first();

        if (! $gallery) {
            return null;
        }
        $gallery->fill([
            'user_id' => Auth::id(),
            'title' => $title,
        ]);

        $gallery->save();
        $file_array = [];
        foreach ($files as $file) {
            $file_array[] = self::uploadFile($file);
        }
        $start_order_number = GalleryDetail::where('gallery_id', $gallery->id)->max('order') + 1;

        $images = [];
        for ($i = 0; $i < count($file_array); $i++) {
            $gallery_detail = new GalleryDetail();
            $gallery_detail->gallery_id = $gallery->id;
            $gallery_detail->origin_filename = $file_array[$i]['original_file_name'];
            $gallery_detail->filename = $file_array[$i]['filename'];
            $gallery_detail->order = $start_order_number++;
            $gallery_detail->file_path = $file_array[$i]['path'];
            $gallery_detail->save();
            $images[] = $gallery_detail;
        }

        return $images;
    }

    protected function getFileValidationCustomErrorMessages()
    {
        return [
            'max'    => 'File can not be exceed :max KB.',
            'image'  => 'File must be image',
        ];
    }

    public static function uploadFile(UploadedFile $file)
    {
        try {
            if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                throw new Exception('Exceed server allow max upload size');
            }
            $origin_file_name = $file->getClientOriginalName();
            $thumbnail = Image::make($file);

            $fileName = uniqid('img_').'_'.$origin_file_name;
            $file->move(self::$gallery_upload_directory, $fileName);

            //create Thumbnail
            $thumbnail->fit(200, 200);
            $thumbnail->save(self::$gallery_upload_directory.'thumbnail/'.$fileName);

            return [
                'original_file_name' => $origin_file_name,
                'filename' => $fileName,
                'path' =>  self::$gallery_upload_directory.$fileName,
                'file_path' => env('APP_URL').self::$gallery_upload_directory.$fileName,
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    protected function deleteFile($file_path)
    {
        // Storage::delete(['file.jpg', 'file2.jpg']);
    }
}
