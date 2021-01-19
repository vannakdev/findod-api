<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FloorCategory extends Model
{

        protected $table = 'floor_category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $table = '';
    protected $fillable = [
        'id',
        'rule',
        'project_name_count',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['translation'];
    protected $appends =['title'];
    //================Residence traislation ============================
    protected $with = ['translation'];

    public function translation()
    {
        return $this->hasMany('App\FloorCategoryTranslation')->where('locale', '=', app('translator')->getLocale());
    }

    public function users()
    {
        return $this->hasMany('App\FloorCategoryTranslation');
    }
    public function getTitleAttribute(){
       if ($this->translation->first()):
            return $this->translation->first()->title;
        endif;
        return '';
    }
}
