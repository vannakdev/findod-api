<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgeCategoryTranslation extends Model
{
    protected $table = 'age_category_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'locale',
        'age_category_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'locale', 'created_at', 'updated_at', 'age_category_id'];

    public function AgeCategory()
    {
        return $this->belongsTo('App\AgeCategory', 'age_category_id');
    }
}
