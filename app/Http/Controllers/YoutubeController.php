<?php

/**
 * Global class for system notification
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_YouTube;
//use Google_Service_Plus;
//use Google_Service_YouTube_VideoSnippet;
//use Google_Service_YouTube_VideoStatus;
use Google_Http_MediaFileUpload;
use Google_Service_YouTube_Video;
use App\YoutubeToken;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of newPHPClass
 *
 * @author OU Sophea : ODIC
 */
class YoutubeController extends Controller {

    public function __construct() {
        
    }

//$client->setRedirectUri('https://api-test.odcambodia.com/api/youtube');

    /**
     * Post video function
     */
    public function backendVideoUpload(Request $request) {
        if (!$request->hasFile('video')) {
            return $this->getResponseData("0", "Data validation failed.", 'The video field is required.');
        }

        $file = $request->file('video');
        if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
            return $this->getResponseData("0", "Data validation failed.", "Larg upload file. " . UploadedFile::getMaxFilesize());
        }

        $validator = Validator::make($request->all(), ['video' => 'required|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',
                    'title' => 'required|min:5|regex:/(^[A-Za-z0-9 ]+$)+/']
        );
        if ($validator->fails()) {
            return $this->getResponseData("0", "Data validation failed.", $validator->errors()->first());
        }

        $videoUpload = $this->youtubeVideoUpload($request, $request->input('title'));
        if (!$videoUpload['status']) {
            return $this->getResponseData("0", "Video upload to youtube faild.", $videoUpload['message']);
        }
        return $this->getResponseData("1", "Video upload to youtube successfully.", $videoUpload['data']);
    }

    /**
     * 
     * @param Request $request
     * @param type $media_file
     * @param type $id
     * @param string $videoName
     * @return type
     */
    public function youtubeVideoUpload(Request $request, $videoName = null) {

        $client = new Google_Client();
        $client->setClientId(env('OAUTH2_CLIENT_ID'));
        $client->setClientSecret(env('OAUTH2_CLIENT_SECRET'));
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = env('APP_URL') . 'api/youtube';
        $client->setRedirectUri($redirect);
        $client->setAccessType("offline");
        $client->setApprovalPrompt("force");


        $youtube = new YoutubeToken();

        $getYoutube = $youtube->get()->first();
        if (isset($getYoutube->access_token)) {
            $accessToken = $getYoutube->access_token;
            $client->setAccessToken($accessToken);
// Refresh the token if it's expired.
            if ($client->isAccessTokenExpired()) {
                $accessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $youtube->access_token = json_encode($accessToken);
                $youtube->update();
            }
        } else {
            if ($request->has('code')) {
                $accessToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));
                $client->setAccessToken($accessToken);
                $youtube = new YoutubeToken();
                $youtube->access_token = json_encode($accessToken);
                $youtube->save();
            } else {
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl);
            }
        }
        $media_file = $request->file('video');
// Define an object that will be used to make all API requests.
        $service = new Google_Service_YouTube($client);
        if ($videoName == NULL) {
            $videoName = "Ocean Property";
        }
        try {
            return [
                'status' => 1,
                'data' => $this->videosInsert($client, $service, $media_file, array('snippet.categoryId' => '22',
                    'snippet.defaultLanguage' => '',
                    'snippet.description' => 'Hosting property video upload.',
                    'snippet.tags[]' => '',
                    'snippet.title' => $videoName,
                    'status.embeddable' => '',
                    'status.license' => '',
                    'status.privacyStatus' => '',
                    'status.publicStatsViewable' => 'true'), 'snippet,status', array())];
        } catch (Exception $ex) {
            return ['status' => 0, 'message' => $ex];
        }
    }

    /**
     * 
     * @param Request $request
     * @param string $videoName
     * @return type
     * 
     */
    public function youtubeVideoUpdate(Request $request, $media_file, $videoId, $videoName = null) {

        $client = new Google_Client();
        $client->setClientId(env('OAUTH2_CLIENT_ID'));
        $client->setClientSecret(env('OAUTH2_CLIENT_SECRET'));
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = env('APP_URL') . 'api/youtube';
        $client->setRedirectUri($redirect);
        $client->setAccessType("offline");
        $client->setApprovalPrompt("force");


        $youtube = new YoutubeToken();

        $getYoutube = $youtube->get()->first();
        if (isset($getYoutube->access_token)) {
            $accessToken = $getYoutube->access_token;
            $client->setAccessToken($accessToken);
// Refresh the token if it's expired.
            if ($client->isAccessTokenExpired()) {
                $accessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $youtube->access_token = json_encode($accessToken);
                $youtube->update();
            }
        } else {
            if ($request->has('code')) {
                $accessToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));
                $client->setAccessToken($accessToken);
                $youtube = new YoutubeToken();
                $youtube->access_token = json_encode($accessToken);
                $youtube->save();
            } else {
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl);
            }
        }
//        $media_file = $request->file('video');
// Define an object that will be used to make all API requests.
        $service = new Google_Service_YouTube($client);
        if ($videoName == NULL) {
            $videoName = "Find OD Cambodia";
        }
        try {
            $result = [
                'status' => 1,
                'data' => $this->videosInsert($client, $service, $media_file, array('snippet.categoryId' => '22',
                    'snippet.defaultLanguage' => '',
                    'snippet.description' => 'Hosting property video upload.',
                    'snippet.tags[]' => '',
                    'snippet.title' => $videoName,
                    'status.embeddable' => '',
                    'status.license' => '',
                    'status.privacyStatus' => '',
                    'status.publicStatsViewable' => 'true'), 'snippet,status', array())
            ];

            if ($this->checkVideo($service, $videoId)):
                $this->videosDelete($service, $videoId, array('onBehalfOfContentOwner' => ''));
            endif;

            return $result;
        } catch (Exception $ex) {
            return ['status' => 0, 'message' => $ex];
        }
    }

    /**
     * 
     * @param Request $request
     * @param type $videoId
     * @return boolean
     */
    public function youtubeVideoDelete(Request $request, $videoId) {

        $client = new Google_Client();
        $client->setClientId(env('OAUTH2_CLIENT_ID'));
        $client->setClientSecret(env('OAUTH2_CLIENT_SECRET'));
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = env('APP_URL') . 'api/youtube';
        $client->setRedirectUri($redirect);
        $client->setAccessType("offline");
        $client->setApprovalPrompt("force");


        $youtube = new YoutubeToken();

        $getYoutube = $youtube->get()->first();
        if (isset($getYoutube->access_token)) {
            $accessToken = $getYoutube->access_token;
            $client->setAccessToken($accessToken);
// Refresh the token if it's expired.
            if ($client->isAccessTokenExpired()) {
                $accessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $youtube->access_token = json_encode($accessToken);
                $youtube->update();
            }
        } else {
            if ($request->has('code')) {
                $accessToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));
                $client->setAccessToken($accessToken);
                $youtube = new YoutubeToken();
                $youtube->access_token = json_encode($accessToken);
                $youtube->save();
            } else {
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl);
            }
        }
//        $media_file = $request->file('video');
// Define an object that will be used to make all API requests.
        $service = new Google_Service_YouTube($client);
        try {

            if (!$this->checkVideo($service, $videoId)):
                return ['status' => FALSE, 'message' => trans('messages.videoNotFound')];
            endif;

            return ['status' =>TRUE , 'data'=> $this->videosDelete($service, $videoId, array('onBehalfOfContentOwner' => ''))];
            
        } catch (Exception $ex) {
            return ['status' => 0, 'message' => $ex];
        }
    }

    function checkVideo($service, $id) {
        $getVideo = $this->videosListById($service, 'snippet,contentDetails,statistics', array('id' => $id));
        if (empty($getVideo->items)):
            return FALSE;
        endif;
        return TRUE;
    }

// Build a resource based on a list of properties given as key-value pairs.
    function createResource($properties) {
        $resource = array();
        foreach ($properties as $prop => $value) {
            if ($value) {
                $this->addPropertyToResource($resource, $prop, $value);
            }
        }
        return $resource;
    }

// Add a property to the resource.
    function addPropertyToResource(&$ref, $property, $value) {
        $keys = explode(".", $property);
        $is_array = false;
        foreach ($keys as $key) {
// For properties that have array values, convert a name like
// "snippet.tags[]" to snippet.tags, and set a flag to handle
// the value as an array.
            if (substr($key, -2) == "[]") {
                $key = substr($key, 0, -2);
                $is_array = true;
            }
            $ref = &$ref[$key];
        }

// Set the property value. Make sure array values are handled properly.
        if ($is_array && $value) {
            $ref = $value;
            $ref = explode(",", $value);
        } elseif ($is_array) {
            $ref = array();
        } else {
            $ref = $value;
        }
    }

    function videosInsert($client, $service, $media_file, $properties, $part, $params) {
        $params = array_filter($params);
        $propertyObject = $this->createResource($properties); // See full sample for function
        $resource = new Google_Service_YouTube_Video($propertyObject);
        $client->setDefer(true);
        $request = $service->videos->insert($part, $resource, $params);
        $client->setDefer(false);
        $response = $this->uploadMedia($client, $request, $media_file, 'video/*');
        return $response;
    }

    function videosUpdate($service, $properties, $part, $params) {
        $params = array_filter($params);
        $propertyObject = createResource($properties); // See full sample for function
        $resource = new Google_Service_YouTube_Video($propertyObject);
        $response = $service->videos->update($part, $resource, $params);
        print_r($response);
    }

    function videosDelete($service, $id, $params) {
        $params = array_filter($params);
        $response = $service->videos->delete(
                $id, $params
        );
        return $response;
    }

    /**
     * 
     */
    function videosListById($service, $part, $params) {
        $params = array_filter($params);
        $response = $service->videos->listVideos(
                $part, $params
        );
        return $response;
    }

    function uploadMedia($client, $request, $filePath, $mimeType) {
// Specify the size of each chunk of data, in bytes. Set a higher value for
// reliable connection as fewer chunks lead to faster uploads. Set a lower
// value for better recovery on less reliable connections.
        $chunkSizeBytes = 1 * 1024 * 1024;

// Create a MediaFileUpload object for resumable uploads.
// Parameters to MediaFileUpload are:
// client, request, mimeType, data, resumable, chunksize.
        $media = new Google_Http_MediaFileUpload(
                $client, $request, $mimeType, null, true, $chunkSizeBytes
        );
        $media->setFileSize(filesize($filePath));


// Read the media file and upload it chunk by chunk.
        $status = false;
        $handle = fopen($filePath, "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        fclose($handle);
        return $status;
    }

// Sample php code for videos.list

    public function videosListMultipleIds($service, $part, $params) {
        $params = array_filter($params);
        $response = $service->videos->listVideos(
                $part, $params
        );

        return ($response);
    }

//=============
}
