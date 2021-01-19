<?php

namespace App;

use App\ChatContact;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatChannel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'user_id',
        'property_id',
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
        'message_deleted_at',
    ];

    protected $appends = [
        // 'partner',
        // 'property',
        'last_message',

    ];

    protected $hidden = [
        'user_id',
        'property_id',
    ];

    const TYPE = [
        'private'   => 'private',
        'public'    => 'public',
        'group'     => 'group',
    ];

    protected $message_flag = ['sent', 'seen'];

    public function participants()
    {
        return $this->hasMany('App\ChatParticipant');
    }

    public function messages()
    {
        return $this->hasMany('App\ChatMessage')
                    ->withTrashed();
    }

    public function property()
    {
        return $this->belongsTo('App\Properties');
    }

    public function partner()
    {
        return $this->hasOne('App\ChatParticipant')->withTrashed()->where('user_id', '<>', Auth::id());
    }

    public function getLastMessageAttribute()
    {
        $message = \App\ChatMessage::where('chat_channel_id', $this->id)
                                   ->with('user')
                                   ->orderBy('created_at', 'DESC')
                                   ->first();
        if (is_null($message)) {
            return '';
        } else {
            return $message;
        }
    }

    public function getDeletedAtAttribute($value)
    {
        if (is_null($value)) {
            return '';
        }

        return $value;
    }

    /**
     * get the Allow Message Flag Enumeration $message_flag.
     * @return array
     */
    public function getMessageFlag()
    {
        return $this->message_flag;
    }

    public function deleteMessage()
    {
        $this->messages()
             ->where('user_id', Auth::id())
             ->delete();
        $participant = $this->participants()->where('user_id', Auth::id())->first();

        return $participant->delete();
    }

    public function restoreParticipants($all_participants = true)
    {
        if ($all_participants) {
            return $this->participants()->restore();
        } else {
            $this->participants()->withTrashed()->where('user_id', Auth::id())->first()->restore();
        }
    }

    /**
     * Check whether the ChatChannel is exist in the Database or not.
     *
     * @param  int $user_id, $property_id, $channel_id
     *         (if $channel_id provided, $user_id and $property_id will be ignore)
     * @return bool : True if Exist, False if not
     */
    public static function exist($user_id, $property_id, $channel_id = null)
    {
        $count = 0;

        if (is_null($channel_id)) {
            $count = self::where('user_id', $user_id)
                         ->where('property_id', $property_id)->count();
        } else {
            $count = self::where('id', $channel_id)
                         ->count();
        }

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check whether the user is exist the chat channel or not.
     *
     * @param  int $user_id, Integer $channel_id
     * @return bool : True if the user_id is allow, False if not
     */
    public static function isParticipantAllow($user_id, $channel_id)
    {
        $count = 0;
        $count = \App\ChatParticipant::where('user_id', $user_id)
                                      ->where('chat_channel_id', $channel_id)
                                      ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getChannelIdsByUserID(int $user_id)
    {
        //Get All Channel id in 1 array which Auth::user() invole;
        $channel_id_array = ChatParticipant::where('user_id', $user_id)
                                               ->pluck('chat_channel_id')
                                               ->toArray();

        //Remove all the channel with no message
        $channel_id_array = ChatMessage::whereIn('chat_channel_id', $channel_id_array)
                                       ->distinct('chat_channel_id')
                                       ->pluck('chat_channel_id')
                                       ->toArray();

        return $channel_id_array;
    }

    public static function getUnreadMessageChannelIdsByUserID(int $user_id)
    {
        $channel_id_array = self::getChannelIdsByUserID($user_id);

        return  ChatMessage::whereIn('chat_channel_id', $channel_id_array)
                                        ->where('flag', 'sent')
                                        ->where('user_id', '<>', $user_id)
                                        ->distinct('chat_channel_id')
                                        ->pluck('chat_channel_id')
                                        ->toArray();
    }
}
