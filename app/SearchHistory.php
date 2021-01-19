<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'search_history';
    protected $fillable = [
        'id',
        'user_id',
        'request_query',
        'request_result',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id'];

    public function users()
    {
        return $this->belongsTo('App\Users', 'use_id')
                        ->select('users.id', 'first_name', 'last_name', 'photo', 'email', 'phone', 'userol_id', 'playerId', 'setting');
    }
}
