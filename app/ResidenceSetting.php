<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidenceSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'residence_setting';
    protected $fillable = ['residence_id', 'property_type_id', 'status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    public function residence()
    {
        return $this->belongsTo(Residence::class, 'residence_id', 'id');
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id', 'id');
    }
}
