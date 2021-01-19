<?php

namespace App;

//use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Residence extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'residences';
    protected $fillable = [
        'id',
        'res_title',
        'res_amenitie'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'res_amenities', 
        'status', 
        'created_at', 
        'updated_at', 
        'position', 
        'color_code',
        'residence_type_id',
        'translation'
    ];
    //=================Residence traislation ============================
    protected $with = ['translation'];

//
    public function getResTitleAttribute($value) {
        if ($this->translation->first()):
            return $this->translation->first()->res_title;
        endif;
        return $value;
    }

    public function translation() {
        return $this->hasMany('App\RecidencesTranslation')->where('locale', '=', app('translator')->getLocale());
    }

    //=============================================
    public function getIconAttribute($value) {
        if (isset($value) AND $value != '') {

            return env('APP_URL') . 'uploads/residences/' . $value;
        }
        return '';
    }

}
