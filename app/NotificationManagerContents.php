<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationManagerContents extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'notification_manager_contents';
    protected $fillable = [
        'language_id', 'content', 'notification_manager_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'deleted_at', 'created_at','notification_manager_id','id'];

    public function getContentAttribute($value) {
        if (isset($value) AND $value != '') {
            
            return $value;
        }
        return "";
    }

}
