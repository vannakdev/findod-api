<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectName extends Model {

    protected $table = 'project_name';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lat', 'lng', 'address', 'hotline', 'tower', 'floor', 'floor_category_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     *
     * @var type 
     */
    protected $hidden = ['created_at', 'updated_at',
        'translation'
    ];
    
    protected $appends =['title'];
    
    //=================Property type traislation ============================
    protected $with = ['translation', 'floor_category'];


    public function translation() {
        return $this->hasMany('App\ProjectNameTranslation')->where('locale', '=', app('translator')->getLocale());
    }

    public function floor_category() {
        return $this->belongsTo('App\FloorCategory');
    }
    
    public function properties()
    {
        return $this->hasMany('App\Properties','project_name_id');
    }

    
    public function getTitleAttribute($value){
       if ($this->translation->first() != NULL):
            return $this->translation->first()->title;
        endif;
        return $value;
    }
    //=============================================

    public static function getProjectByDistance($lat, $lng, $distance,$residence_id,$selectColumns) {
        $haversineSQL = '(111.111 *
            DEGREES(ACOS(COS(RADIANS(' . $lat . '))
         * COS(RADIANS(lat))
         * COS(RADIANS(' . $lng . ' - lng))
         + SIN(RADIANS(' . $lat . '))
         * SIN(RADIANS(lat)))))';
        $projects = ProjectName::select(DB::raw($selectColumns . ',' . $haversineSQL . ' AS distance_in_km'))
                ->where('residences_id','LIKE', '%'.$residence_id.'%')
                ->whereRaw($haversineSQL . '<= ?', [$distance])
                ->orderBy('distance_in_km', 'ASC')->get();
        return $projects;
    }
    

}
