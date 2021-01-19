<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyTypeTranslation extends Model
{
    protected $table = 'property_type_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'locale',
        'property_type_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'locale', 'created_at', 'updated_at', 'property_type_id'];

    public function PropertyType()
    {
        return $this->belongsTo('App\PropertyType', 'property_type_id');
    }
}
