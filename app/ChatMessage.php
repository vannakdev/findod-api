<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_channel_id',
        'user_id',
        'content',
        'flag',
    ];

    /**
     * The attributes that are needed to cast to Carbon DateTime Object.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'user_id',
        'chat_channel_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Users')->select(['id', 'first_name', 'last_name', 'photo', 'playerId']);
    }

    public function getDeletedAtAttribute($value)
    {
        if (is_null($value)) {
            return '';
        }

        return $value;
    }
}
