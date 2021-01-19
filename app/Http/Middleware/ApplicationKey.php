<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;


class ApplicationKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( !$request->header('x-application-key') ){
            return response( [ 'status'=> 'unauthorized' , 'message' => 'Unauthorized Access' ,'code' => '401' ] , 401  );
        }
        if ( strcmp($request->header('x-application-key') , env('APP_X_APP_KEY') ) != 0 ){
            return response( [ 'status'=> 'unauthorized' , 'message' => 'Unauthorized Access' ,'code' => '401' ] , 401  );
        }

        return $next($request);
    }
}
