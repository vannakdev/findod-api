<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatParticipant extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_channel_id',
        'user_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // protected $hidden = [
    //     'user_id',
    //     'chat_channel_id'
    // ];

    protected $appends = [
        'user'
    ];

    public function getUserAttribute()
    {
        $user = new \App\Users();
        return \App\Users::where($user->getKeyName(), $this->user_id)
                         ->select(['id', 'first_name', 'last_name', 'photo' , 'playerId'])
                         ->first();
    }

    public function getDeletedAtAttribute($value)
    {
        if (is_null($value)) {
            return "";
        }
        return $value;
    }

    public function channel()
    {
        return $this->belongsTo('App\ChatChannel')->withDefault();
    }
}
