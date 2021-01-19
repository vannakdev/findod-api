<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'advertisements';
    protected $fillable = ['view_count'];
    protected $hidden = ["created_at", "updated_at", 'status'];
    protected $appends = ['url'];



    public function getThumbnailAttribute() {
        return env('APP_URL') . 'uploads/advertisements/thumbnails/thumbnail-'.$this->getOriginal('feature_image');;
    }

    public function getUrlAttribute() {
        return env('WEPSITE_WEP_URL') . $this->id;
    }

    public function getFeatureImageAttribute($value) {
        if (isset($value) AND $value != '') {
            return env('APP_URL') . 'uploads/advertisements/' . $value;
        }
        return env('APP_URL') . 'uploads/advertisements/sample.jpeg';
    }

    public function tags() {
        return $this->belongsToMany('App\Tags','advertisement_tags','advertisement_id','tag_id');
    }

    public function user() {
        return $this->belongsTo('App\Users');
    }

}
