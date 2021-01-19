<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Validator;

class GoogleServiceController extends Controller
{
    public function submitRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
                                        'url' => 'required',
                    ]);

        if ($validator->fails()) {
            return $this->getResponseData('0', 'Data validation failed.', $validator->errors()->first());
        }

        //Please make you have GOOGLE_API_KEY in .env file
        //The Google API Key should allow the IP of the All Request Servers
        $client = new Client();
        $request_url = $request->input('url').'&key='.config('app.google_api_key');
        if (config('app.env') === 'production') {
            $response = $client->request('GET', $request_url);
        } else {
            $response = $client->request('GET', $request_url, ['verify' => false]);
        }

        return $response->getBody();
    }
}
