<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidenceTypeTranslation extends Model
{
    protected $table = 'residence_type_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'locale',
        'residence_type_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'locale', 'created_at', 'updated_at', 'residence_type_id'];

    public function ResidenceType()
    {
        return $this->belongsTo('App\ResidenceType', 'residence_type_id');
    }
}
