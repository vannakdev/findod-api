<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
  public function getResponseData( String $status, String $message, $data ){
  
  	return response()->json(['status' => $status , 'message' => $message , 'data' => $data ]);

  }
}
