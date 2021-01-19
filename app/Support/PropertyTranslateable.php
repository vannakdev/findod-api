<?php

namespace App\Support;

//use Illuminate\Http\Request;

trait PropertyTranslateable
{
//    public $requestData;

    //=====================Run insert/update chaild table
    protected static function boot()
    {
        parent::boot();

        static::create(function ($model) {
            //Let's get our supported configurations from the config file we've created
            $languages = ['en', 'kh', 'cn'];

//            $request = $model->request;
//            foreach ($model->PropertyTranslateable as $key) {
//                $data[$key] = $model->$key;
//            }

            foreach ($languages as $language) {
//                $data['locale'] = $language;
                $model->translation()->create(['locale'=>$language]);
//                $model->translation()->updateOrCreate(['properties_id' => $model->id], $data);
            }
        });
    }
}
