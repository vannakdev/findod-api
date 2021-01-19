<?php

namespace App;

use App\Support\Translateable;
use Illuminate\Database\Eloquent\Model;

class Amenities extends Model
{
    use Translateable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'icon',
    ];
    public $timestamps = false;
    public $translationAble = ['title'];
    public static $request = '';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'status',
        'pivot',
//        'translation'
    ];
//    public function getTitleAttribute() {
//        return $this->translation->first()->title;
//    }
    //=================Amenity traislation ============================
    protected $with = ['translation'];

    public function translation()
    {
        return $this->hasMany('App\AmenitiesTranslation', 'amenity_id')->where('locale', '=', app('translator')->getLocale());
    }

    public function getIconAttribute($value)
    {
        if (isset($value) and $value != '') {
            return env('APP_URL').'uploads/amenities/'.$value;
        }

        return [env('APP_URL').'uploads/amenities/samplePhoto.png'];
    }

    /**
     * The amenities that belong to the property.
     */
    public function properties()
    {
        return $this->belongsToMany('App\Properties', 'amenity_property', 'amenities_id', 'property_id');
    }
}
