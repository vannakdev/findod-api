<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'currency';
    protected $fillable = [
        'id',
        'title',
        'sign'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id'];
    

    public function properties()
    {
        return $this->hasMany('App\Properties');
    }

}
