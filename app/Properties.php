<?php

namespace App;

//use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\PropertyScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

//use Illuminate\Support\Carbon;
//use App\Support\PropertyTranslateable;
//use App\Support\Translateable;

class Properties extends Model {

    use SoftDeletes;
//    use PropertyTranslateable;
//    use Translateable;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    static protected $default_residence = 9;
    static protected $number_per_page = 25;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot() {
        parent::boot();

        static::addGlobalScope(new PropertyScope);
        static::created(function($model) {
//=================Get suppoort languege===============================================
            $list = [];
            $languages = $model->languageSupport();
            $getLocale = app('translator')->getLocale();
            foreach ($languages as $language):
                app('translator')->setLocale($language);
                $data['locale'] = $language;
                $data['pro_title'] = $model->ganerateProTitle($model);
                array_push($list, $data);
                //$model->translation()->updateOrCreate(['properties_id' => $model->id], $data);
            endforeach;
        });
        static::updated(function($model) {
//=================Get suppoort languege===============================================
            $languages = $model->languageSupport();
            $getLocale = app('translator')->getLocale();
            foreach ($languages as $language):
                app('translator')->setLocale($language);
                $data['locale'] = $language;
                $data['pro_title'] = $model->ganerateProTitle($model);
                $model->translation()->updateOrCreate(['properties_id' => $model->id], $data);
            endforeach;
            app('translator')->setLocale($getLocale);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_title',
        'pro_price',
//        'pro_land_mark', 
        'pro_floor', 'pro_square_feet', 'pro_bed_rooms', 'pro_bath_rooms',
        'pro_parking', 'pro_detail', 'pro_age', 'pro_city', 'pro_state', 'pro_zip', 'pro_address', 'pro_search_type',
        'pro_lat', 'pro_lng',
        'pro_photos',
        'pro_public_id',
        'pro_use_id', 'pro_plan', 'pro_videos', 'pro_currency', 'pro_view_counter',
        'pro_residence',
        'pro_status', 'deleted_at',
        'pro_thumbnail',
        'favorites_count',
        'request_viewing_count',
        'comment_count',
        'pro_contact_name',
        'pro_contact_number',
        'pro_contact_email',
        'project_name_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        "currency_id",
        "pro_user_view_point",
        "pro_residence",
        "pro_email",
//        "pro_contact_name",
//        "pro_contact_number",
        "pro_currency",
        "pro_unite_price",
        "pro_month",
        "deleted_at",
        "pro_square_price",
//        "pro_contact_email",
        "pro_use_id",
//        'pro_thumbnail',
//        'favorites',
        'pro_photos',
        'pro_search_type',
//        'translation',
        'pro_land_mark',
        'propertyPhotos',
        'request_viewing_count',
//        'comment_count',
        'pro_plan'
    ];
//=================Property traislation ============================
    protected $with = ['propertyType',
        'users',
        'translation'
    ];
    
    

//    static $translationAble = ['pro_title'];
//    static $foreignKey = ['properties_id'];

    public function getProTitleAttribute($value) {
        if ($this->translation->first()):
            return $this->translation->first()->pro_title;
        endif;
        return $value;
    }

    public function translation() {
        return $this->hasMany('App\PropertiesTranslations', 'properties_id')->where('locale', app('translator')->getLocale());
    }

//=============================================
//==================V1 get one photo when view property===========================
//    public function getProPhotosAttribute($value) {
//        if (isset($value) AND $value != '') {
//            $photos = json_decode($value);
//            foreach ($photos as $photo) {
//                return env('APP_URL') . 'uploads/property_images/' . $photo;
//            }
//        }
//        return [env('APP_URL') . 'uploads/' . 'uploads/property_images/samplePhoto.png'];
//    }
//    public function getProPhotosAttribute($value) {
//        return $this->pro_thumbnail;
//    }

    public function getProThumbnailAttribute($value) {
        if (isset($value) AND $value != '') {
            return env('APP_URL') . 'uploads/property_images/thumbnails/' . $value;
        }
        return [env('APP_URL') . 'uploads/property_images/thumbnails/thumbnail-sample.jpeg'];
    }

//    public function getProPlanAttribute($value) {
//        if (isset($value) AND $value != '' AND $value) {
//            $plans = json_decode($value);
//            foreach ($plans as $plan) {
//                return env('APP_URL') . 'uploads/property_plan_images/' . $plan;
//            }
//        }
//        return [env('APP_URL') . 'uploads/' . 'uploads/property_plan_images/samplePlan.png'];
//    }

    public function getProVideosAttribute($value) {
        if (isset($value) AND $value != '') {
            return $value;
        }
        return '';
    }

    public function getProjectNameIdAttribute($value) {
        if (isset($value) AND $value != '') {
            return $value;
        }
        return '';
    }

//    public function getProSearchTypeAttribute($value) {
//        if ($value == 1) {
//            return "Sale";
//        } else {
//            return "Rent";
//        }
//    }
//    public function getProResidenceAttribute($value) {
//        if ($value != NULL) {
//            return $value;
//        } else {
//            return 0;
//        }
//    }
//
//    public function setProResidenceAttribute($value) {
//        if (isset($value) AND $value != '') {
//            $this->attributes['pro_residence'] = $value;
//        } else {
//            $this->attributes['pro_residence'] = self::$default_residence;
//        }
//    }
    public function setProjectNameIdAttribute($value) {
        if (isset($value) AND $value != '') {
            $this->attributes['project_name_id'] = $value;
        } else {
            $this->attributes['project_name_id'] = NULL;
        }
    }

    /**
     * List of photos for a property.
     */
    public function propertyPhotos() {
        return $this->hasMany('App\Photo', 'properties_id');
    }

    /**
     * The property that have many rating.
     */
    public function rating() {
        return $this->hasMany('App\Ratings');
    }

    /**
     * The property that can be belong to one project name.
     */
    public function project_name(){
        return $this->belongsTo('App\ProjectName', 'project_name_id');
    }

    /**
     * The property that have many trending.
     */
    public function trending() {
        return $this->hasMany('App\Trendings', 'tre_pro_id')->orderBy('tre_date', 'desc')->orderBy('tre_counter', 'desc');
    }

    public function users() {
        return $this->belongsTo('App\Users', 'pro_use_id')
                ->with('role')
                        ->select('users.id', 'first_name', 'last_name', 'photo', 'email', 'phone', 'status', 'userol_id', 'playerId', 'setting','created_at')
                        ->where('status', 1);
    }

    /**
     * The property that have many photos. 
     */
    public function plans() {
        return $this->hasMany('App\Plan', 'properties_id');
    }

    public function currency() {
        return $this->belongsTo('App\Currency', 'pro_currency');
    }

    /**
     * The amenities that belong to the property.
     */
    public function amenities() {
        return $this->belongsToMany('App\Amenities', 'amenity_property', 'property_id');
    }

    public function residence() {
        return $this->belongsTo('App\Residence', 'pro_residence');
    }

    /**
     * The property that belong to the user.
     */
    public function favorites() {
        return $this->belongsToMany('App\Users', 'favorites', 'properties_id');
    }

    /**
     * The property that belong to the user.
     */
    public function propertyType() {
        return $this->belongsTo('App\PropertyType', 'pro_search_type');
    }

    /**
     * The property have request viewing a user.
     */
    public function request_viewing() {
        return $this->belongsToMany('App\Users', 'request_property', 'property_id');
    }

    public static function getByDistance($lat, $lng, $distance) {
        $results = DB::select(DB::raw('SELECT id, '
                                . '( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( pro_lat ) ) * cos( radians( pro_lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians(pro_lat) ) ) ) AS distance '
                                . 'FROM properties HAVING distance <= ' . $distance . ' ORDER BY distance'));
        return $results;
    }

    public function getDistance($property_id, $params) {
        $local = explode(',', $params);

        if (!is_array($local)) {
            return 0;
        }
        if (!isset($local[0]) && $local[0] != "") {
            return 0;
        }
        if (!isset($local[1])) {
            return 0;
        }
        $lat = $local[0];
        $lng = $local[1];
        $haversineSQL = '(111.111 *
            DEGREES(ACOS(COS(RADIANS(' . $lat . '))
         * COS(RADIANS(pro_lat))
         * COS(RADIANS(' . $lng . ' - pro_lng))
         + SIN(RADIANS(' . $lat . '))
         * SIN(RADIANS(pro_lat)))))';
        $properties = Properties::select(DB::raw('* ,' . $haversineSQL . ' AS distance_in_km'))
                        ->where('id', $property_id)->first();
        return $properties->distance_in_km;
    }

//    public static function getPropertyByDistance($lat, $lng, $distance) {
//        $results = Properties::select(DB::raw('( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( pro_lat ) ) * cos( radians( pro_lng )'
//                                . ' - radians(' . $lng . ') )'
//                                . ' + sin( radians(' . $lat . ') ) * sin( radians(pro_lat) ) ) ) AS distance ')
//                )
////                ->select(DB::raw(' HAVING distance <= ' . $distance))
//                ->get()->toArray();
//        dd($results);
//        return $results;
//    }

    /**
     * 
     * @param type $lat
     * @param type $lng
     * @param type $distance
     * @param type $selectColumns
     * @param type $number_per_page
     * @return type
     */
    public static function getPropertyByDistance($lat, $lng, $distance, $selectColumns = null, $number_per_page = null) {
        $haversineSQL = '(111.111 *
            DEGREES(ACOS(COS(RADIANS(' . $lat . '))
         * COS(RADIANS(pro_lat))
         * COS(RADIANS(' . $lng . ' - pro_lng))
         + SIN(RADIANS(' . $lat . '))
         * SIN(RADIANS(pro_lat)))))';
        $properties = Properties::select(DB::raw($selectColumns . ',' . $haversineSQL . ' AS distance_in_km'))
                ->whereRaw($haversineSQL . '<= ?', [$distance])
                ->with('currency', 'residence', 'amenities', 'project_name')
                ->withCount('favorites')
                ->orderBy('distance_in_km', 'ASC')
                ->orderBy('pro_rating', 'DESC');
        return $properties;
    }

    /**
     * 
     * @param type $lat
     * @param type $lng
     * @param type $selectColumns
     * @param type $main_list
     * @return type
     */
    public static function getPropertyDistanceByCurrenctLocation($lat, $lng, $selectColumns = null, $main_list = null) {
        $user = Auth::user();
        $haversineSQL = '(111.111 *
            DEGREES(ACOS(COS(RADIANS(' . $lat . '))
         * COS(RADIANS(pro_lat))
         * COS(RADIANS(' . $lng . ' - pro_lng))
         + SIN(RADIANS(' . $lat . '))
         * SIN(RADIANS(pro_lat)))))';
        $properties = Properties::select(DB::raw($selectColumns . ',' . $haversineSQL . ' AS distance_in_km'))
                ->with('currency', 'residence', 'amenities', 'project_name')
                ->withCount([// get numnber of favorit user from the pivot table
                    'favorites as favorited' => function($query) use ($user) {
                        $query->where('users_id', $user->id); //condition on user id getting from favorite table
                    }
                ])
                ->orderBy('updated_at', 'DESC')
                ->orderBy('distance_in_km', 'ASC')
                ->whereIn('id', $main_list)
                ->get();
        return $properties;
    }

    /**
     * 
     * @param type $lat
     * @param type $lng
     * @param type $selectColumns
     * @param type $main_list
     * @return type
     */
    public static function getPropertyDistanceByCurrenctLocationByWeb($lat, $lng, $selectColumns = null, $main_list = null,$sort = null,$data = null) {
        $user = Auth::user();
        $haversineSQL = '(111.111 *
            DEGREES(ACOS(COS(RADIANS(' . $lat . '))
         * COS(RADIANS(pro_lat))
         * COS(RADIANS(' . $lng . ' - pro_lng))
         + SIN(RADIANS(' . $lat . '))
         * SIN(RADIANS(pro_lat)))))';
        $properties = Properties::select(DB::raw($selectColumns . ',' . $haversineSQL . ' AS distance_in_km'))
                        ->with('currency', 'residence', 'amenities', 'project_name')
                        ->withCount([// get numnber of favorit user from the pivot table
                            'favorites as favorited' => function($query) use ($user) {
                                $query->where('users_id', $user->id); //condition on user id getting from favorite table
                            }
                        ])
                        //->orderBy('distance_in_km', 'ASC')
                        ->whereIn('id', $main_list)
                        ->orderBy($sort, $data)->paginate(self::$number_per_page);
        return $properties;
    }

    public static function getPropertyByPolygon($request, $selectColumns) {
        $results = DB::select(DB::raw("SELECT * FROM (
SELECT " . $selectColumns . " , 
ST_Within(ST_GEOMFROMTEXT(concat('POINT(',pro_lat,' ',pro_lng,')')), 
ST_GEOMFROMTEXT(concat('POLYGON(('," . $request . ",'))')))As geoFenceStatus
FROM properties pro) AS getPolygon
where geoFenceStatus =1"));
        return $results;
    }

    public function getPropertyByAmenities($request) {

        $counter = count($request->amenities);
        $amenitie = DB::table('amenity_property')->select('property_id')
                ->whereIn('amenities_id', $request->amenities)
                ->groupBy('property_id')
                ->havingRaw("COUNT(property_id) >= $counter")
                ->get();
        $ids = array();
        if (empty($amenitie)) {
            return $ids;
        }
//Extract the id's
        foreach ($amenitie as $q) {
            array_push($ids, $q->property_id);
        }
        return $ids;
    }

    public static function getLastUpdate($request) {
        $getLastUpdate = Properties::get();
        return $getLastUpdate;
    }

    public function updateRating($id) {
        $getPropertyRating = Ratings::where('property_id', $id)
                ->groupBy('property_id')
                ->avg('stars');
        $this->where('id', $id)
                ->update(['pro_rating' => $getPropertyRating]);
    }

    public function languageSupport() {
        $languageDirectory = File::directories(base_path() . '/resources/lang/');
        $languages = [];
        foreach ($languageDirectory as $strLanguage):
        array_push($languages, substr($strLanguage, -2));
        endforeach;
        return $languages;
        }


        /**
         * Makes translation fall back to specified value if definition does not exist
         *
         * @param string $key
         * @param null|string $fallback
         * @param null|string $locale
         * @param array|null $replace
         *
         * @return array|\Illuminate\Contracts\Translation\Translator|null|string
         */
        function trans_fb($key, $fallback = null,  $locale = null, $replace = [])
        {
        if (\Illuminate\Support\Facades\Lang::has($key, $locale)) {
        return trans($key, $replace, $locale);
        }

        return $fallback;
    }

    public function ganerateProTitle($model) {
        $residence = \App\Residence::where('id', $model->pro_residence)->first();
        $propertyType = \App\PropertyType::where('id', $model->pro_search_type)->first();
        $str = 'messages.' . $model->pro_city;
        $pro_title = $residence->res_title .
                trans('messages.conjunction_for') .
                $propertyType->title.
                trans('messages.conjunction_in') .
                $this->trans_fb($str," ".$model->pro_city);
        return $pro_title;
    }

    public static function getPropertyByCurrenctLocation($lat, $lng, $distance,$selectColumns = null, $main_list = null){
		$user = Auth::user();
        $haversineSQL = '(111.111 *
            DEGREES(ACOS(COS(RADIANS(' . $lat . '))
         * COS(RADIANS(pro_lat))
         * COS(RADIANS(' . $lng . ' - pro_lng))
         + SIN(RADIANS(' . $lat . '))
         * SIN(RADIANS(pro_lat)))))';
        $properties = Properties::select(DB::raw($selectColumns . ',' . $haversineSQL . ' AS distance_in_km'))
                ->with('currency', 'residence', 'amenities', 'project_name')
                ->withCount([// get numnber of favorit user from the pivot table
                    'favorites as favorited' => function($query) use ($user) {
                        $query->where('users_id', $user->id); //condition on user id getting from favorite table
                    }
                ])
				->whereRaw($haversineSQL . '<= ?', [$distance])
				->whereIn('id', $main_list)
                ->orderBy('updated_at', 'DESC')
                ->orderBy('distance_in_km', 'ASC')
                ->get();
        return $properties;
    }
    
    public static function getLatestViewProperties($selectColumns = null,$main_list = null){
		$user = Auth::user();
        $properties = Properties::select(DB::raw($selectColumns))
                ->with('currency', 'residence', 'amenities', 'project_name')
                ->withCount([// get numnber of favorit user from the pivot table
                    'favorites as favorited' => function($query) use ($user) {
                        $query->where('users_id', $user->id); //condition on user id getting from favorite table
                    }
                ])
				->whereIn('id', $main_list)
                ->orderBy('pro_view_counter', 'DESC')
                ->get();
        return $properties;
	}

//    public function setProPlanAttribute($value) {
//        if ($value != NULL) {
//            $this->attributes['pro_plan'] = $value;
//        } else {
//            $this->attributes['pro_plan'] = NULL;
//        }
//    }
//
//    public function getPropertyByFavoriteUser($user) {
//        $propertyList = Users::with(['favorites' => function($q) use($user) {
//                        $q->where('users_id', $user->id);
//                    }])->get()->toArray();
//        return $propertyList;
//    }
}
