<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Notification_type extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'notification_type';
    protected $fillable = [
        'title',
        'content'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id','created_at','updated_at'];
    

//    public function notification()
//    {
//        return $this->hasMany('App\Notification');
//    }

}
