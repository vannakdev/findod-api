<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationManager extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'notification_manager';
    protected $fillable = [
        'module', 'event', 'type'
    ];
//    protected $with = ['notifyContent'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'deleted_at', 'event', 'id', 'created_at'];

    
    /**
     * The Notification that have many contents.
     */
    public function notifyContent() {
        return $this->hasMany('App\NotificationManagerContents')->where('locale', app('translator')->getLocale());
    }

}
