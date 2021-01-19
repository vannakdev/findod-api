<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tags';
    protected $fillable = [
        'id',
        'title',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function advertisement()
    {
        return $this->belongsToMany('App\Advertisement', 'advertisement_tags', 'tag_id', 'advertisement_id');
    }
}
