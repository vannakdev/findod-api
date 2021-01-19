<?php

namespace App\Http\Middleware;

use App;
use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Check header request and determine localizaton
        $local = ($request->hasHeader('x-localization')) ? $request->header('x-localization') : 'en';
        // Check support language
        $supportLanguages = $this->languageSupport();
        if (! in_array($local, $supportLanguages)) :
            return response(['status' => '0', 'message' => 'Languege not support.', 'data' => ''], 401);
        endif;
//        ===============================================
        // set laravel localization
        //tmp support languge

        app('translator')->setLocale($local);
        // continue request
        return $next($request);
    }

    public function languageSupport()
    {
        $languageDirectory = \Illuminate\Support\Facades\File::directories(base_path().'/resources/lang/');
        $languages = [];
        foreach ($languageDirectory as $strLanguage) :
            array_push($languages, substr($strLanguage, -2));
        endforeach;

        return $languages;
    }
}
