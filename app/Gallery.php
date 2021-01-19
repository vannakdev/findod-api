<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['user_id', 'key', 'title'];

    public function images()
    {
        return $this->hasMany('App\GalleryDetail')->orderBy('order', 'ASC');
    }
}
