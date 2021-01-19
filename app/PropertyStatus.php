<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyStatus extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $table = 'property_status';
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ["created_at","updated_at","status"];
    
        
    protected $with = ['translation'];

    public function translation() {
        return $this->hasMany('App\PropertyStatusTranslation')->where('locale', '=', app('translator')->getLocale());
    }

    /**
     * The amenities that belong to the property.
     */
    public function properties() {
        return $this->hasMany('App\Properties');
    }
}
