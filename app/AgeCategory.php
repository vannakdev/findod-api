<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgeCategory extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'age_category';
    protected $fillable = [];
    protected $hidden = ["created_at", "updated_at",'status'];
    
    
    
    protected $with = ['translation'];

    public function translation() {
        return $this->hasMany('App\AgeCategoryTranslation')->where('locale', '=', app('translator')->getLocale());
    }

}
