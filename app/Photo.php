<?php

namespace App;

//use Laravel\Scout\Searchable; 
use Illuminate\Database\Eloquent\Model;

class Photo extends Model {

    /**
     * The attributes that are mass assignable. 
     * 
     * @var array 
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes excluded from the model's JSON form. 
     * 
     * @var array 
     */
    protected $hidden = ['properties_id', 'created_at', 'updated_at'];

    public function properties() {
        return $this->belongsTo('App\Properties', 'properties_id');
    }

    public function getNameAttribute($value) {
        if (isset($value) AND $value != '') {
            return env('APP_URL') . 'uploads/property_images/' . $value;
        }
    }

}
