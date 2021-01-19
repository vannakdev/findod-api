<?php

namespace App;

//use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class AmenitiesTranslation extends Model {

    protected $table = 'amenities_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'locale',
        'amenity_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'created_at', 'updated_at','amenity_id','locale'];

    public function Amenities() {
        return $this->belongsTo('App\Amenities', 'amenity_id');
    }

}
