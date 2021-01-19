<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
//    protected $appends = [];
//    protected $with = ['translation'];

//    public $translatefill;
    protected $table = 'properties_translations';
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'pro_title' => 'array',
    ];

    /**
     * Get all of the owning translatable models.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function translatable()
    {
//        return $this->morphTo();
        return $this->hasMany('App\PropertiesTranslation')->where('locale', '=', app('translator')->getLocale());
    }
}
