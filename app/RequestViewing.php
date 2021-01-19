<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestViewing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'request_property';
    protected $fillable = [
        'users_id',
        'property_id',
        'description',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function users()
    {
        return $this->belongsTo('App\Users', 'users_id');
    }

    public function properties()
    {
        return $this->belongsTo('App\Properties', 'property_id');
    }
}
