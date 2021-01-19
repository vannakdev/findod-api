<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $table = 'ratings';
    protected $fillable = [
        'title', 'user_id', 'notification_type_id', 'notification_manager_id', 'sender_id', 'properties_id', 'status', 'comments', 'notification_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'deleted_at', 'notification_type_id', 'user_id', 'sender_id', 'notification_type'];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getCommentsAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return $value;
    }

    public function users()
    {
        return $this->belongsTo('App\Users', 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo('App\Users', 'sender_id')
                        ->select('users.id', 'first_name', 'last_name', 'photo', 'email');
    }

    public function properties()
    {
        return $this->belongsTo('App\Properties');
    }

    public function notification_type()
    {
        return $this->belongsTo('App\Notification_type');
    }

    public function notification_manager()
    {
        return $this->belongsTo('App\NotificationManager', 'notification_manager_id');
    }

    /*
     * Return list of use nearby given lat & lng
     */

    public static function getUserByDistance($lat, $lng, $distance)
    {
        $results = DB::select(DB::raw('SELECT id,playerId, '
                                .'( 3959 * acos( cos( radians('.$lat.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians(lat) ) ) ) AS distance '
                                .'FROM users HAVING distance <= '.$distance.' ORDER BY distance'));

        return $results;
    }
}
