<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $table = 'feedback';
    protected $fillable = [
        'name',
        'email',
        'message'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ["id","deleted_at"];
    
    

}
