<?php

namespace App\Support;

use Illuminate\Http\Request;

trait Translateable
{
    public $requestData;

    /**
     * Get the translation attribute.
     *
     * @return \App\Translation
     */
//    public function getTranslationAttribute() {
//         return $this->hasMany('App\PropertiesTranslation')->where('locale', '=', app('translator')->getLocale());
//        return $this->translations->firstWhere('locale',app('translator')->getLocale());
//    }
    //=====================Run insert/update chaild table
    protected static function boot()
    {
        parent::boot();
        static::saved(function ($model) {

//Let's get our supported configurations from the config file we've created
//            $languages = ['en', 'kh', 'cn'];
            $language = 'en';
            ////            $request = new Request();
//            dd($this->requestData);

//            $request = $model->request;

//            foreach ($model->translationAble as $key) {
//                $data[$key] = $request->input($key);
//            }

            $data['locale'] = $language;
            $data['pro_title'] = 'test title (en)';
            $model->translation()->updateOrCreate(['properties_id' => $model->id], $data);

            //===============Create all support language======================
//            foreach ($languages as $language) {
//                $data['locale'] = $language;
//                $model->translation()->update($data);
//            }
        });
    }
}
