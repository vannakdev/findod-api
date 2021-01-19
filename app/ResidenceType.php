<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidenceType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'residence_type';
    protected $fillable = ['status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'created_at', 'updated_at', 'status'];

    //================Residence traislation ============================
    protected $with = ['translation'];

    public function translation()
    {
        return $this->hasMany('App\ResidenceTypeTranslation')->where('locale', '=', app('translator')->getLocale());
    }

    /**
     * The residence_type that have many residence.
     */
    public function residence()
    {
        return $this->hasMany('App\Residence');
    }
}
