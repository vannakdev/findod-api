<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class FloorCategoryTranslation extends Model {

    
     protected $table = 'floor_category_translations';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'locale',
        'floor_category_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id','locale', 'created_at', 'updated_at','floor_category_id'];

    public function PropertyType() {
        return $this->belongsTo('App\FloorCategory', 'floor_category_id');
    }
}
