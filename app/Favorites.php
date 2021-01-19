<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['properties_id','users_id' ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    /**
     * The amenities that belong to the property.
     */
    public function properties() {
        return $this->belongsTo('App\Properties', 'property_id');
    }
    
        /**
     * The amenities that belong to the property.
     */
    public function users() {
        return $this->belongsTo('App\Users', 'users_id');
    }

}
