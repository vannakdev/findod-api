<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecidencesTranslation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'residence_translates';
    protected $fillable = [
        'res_title',
        'locale',
        'residence_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'created_at', 'updated_at'];
}
