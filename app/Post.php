<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable as Translatable;

class Post extends Model
{
    use SoftDeletes, Translatable;

    /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
    protected $fillable = ['user_id','title','slug','content','visibility'];
    /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
    protected $hidden = ['visibility','deleted_at','translations'];


    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at','updated_at', 'created_at'];

    /**
    * The attributes that should be casts to dates.
    *
    * @var array
    */
    protected $casts = [
        'protected' => "boolean"
    ];

    /**
    * The attributes that should be translate.
    *
    * @var array
    */
    public $translatedAttributes = ['title', 'content', 'meta_keyword', 'meta_description'];
    public $useTranslationFallback = true;


    public function author()
    {
        return $this->belongsTo('App\Users', 'user_id', 'id')->select(['id', 'first_name', 'last_name', 'photo' , 'playerId']);
    }

    /**
     * Scope a query to only published post.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('visibility', 'published');
    }
}

class PostTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title','content','meta_keyword','meta_description'];
}
