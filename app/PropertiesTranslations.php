<?php

namespace App;

//use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
//use App\Scopes\PropertyScope;
use Illuminate\Support\Facades\DB;

//use App\Users;
//use Illuminate\Support\Carbon;

class PropertiesTranslations extends Model {
//    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    static protected $default_residence = 9;
    protected $table = 'properties_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_title',
        'locale',
        'properties_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id', 'created_at', 'updated_at','properties_id','locale'];

    public function Properties() {
        return $this->belongsTo('App\Properties','properties_id');
    }

}
