<?php

namespace App;

//use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class YoutubeToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'youtube_access_tokens';

    protected $fillable = [
       'id', 'access_token', 'refresh_token', ];
}
