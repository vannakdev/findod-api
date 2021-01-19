<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class PropertyStatusTranslation extends Model {

    
     protected $table = 'property_status_translations';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'locale',
        'property_status_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id','locale', 'created_at', 'updated_at','property_status_id'];

    public function PropertyStatus() {
        return $this->belongsTo('App\PropertyStatus', 'property_status_id');
    }
}
