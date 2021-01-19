<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $table = 'property_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     *
     * @var type
     */
    protected $hidden = ['status', 'created_at', 'updated_at',
//        'translation'
    ];

    //=================Property type traislation ============================
    protected $with = ['translation'];

    public function getTitleAttribute($value)
    {
        if ($this->translation->first()):
            return $this->translation->first()->title;
        endif;

        return $value;
    }

    public function translation()
    {
        return $this->hasMany('App\PropertyTypeTranslation')->where('locale', '=', app('translator')->getLocale());
    }

    //=============================================
}
