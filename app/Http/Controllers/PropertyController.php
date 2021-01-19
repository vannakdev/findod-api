<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Http\Controllers\ResponderController;
use App\Properties;
use App\Residence;
//use App\Amenities;
use App\Trendings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;

//use Illuminate\Support\Facades\Schema;
//use Illuminate\Support\Carbon;
//use Symfony\Component\HttpFoundation\File\File;
//use App\Notification;

class PropertyController extends Controller
{
    protected static $number_per_page = 25;
    protected static $distance = 1000;
    protected static $admin_role = 1;
    protected static $minPhoto = 3;
    protected static $maxPhoto = 15;
    protected static $property_video_path = __DIR__.'/../../../public/uploads/property_videos/';
    protected static $property_photos_path = __DIR__.'/../../../public/uploads/property_images/';
    protected static $property_plan_path = __DIR__.'/../../../public/uploads/property_plan_images/';
    protected static $amenity_directory = __DIR__.'/../../../public/uploads/amenities/';
    protected static $residence_directory = __DIR__.'/../../../public/uploads/residences/';
    protected static $advertisement_directory = __DIR__.'/../../../public/uploads/advertisements/';
    protected static $test_photos_path = __DIR__.'/../../../public/uploads/test/';

    public function __construct()
    {
    }

    public function showAllProperty()
    {
        $paginator = Properties::paginate(self::$number_per_page);
        $properties = $paginator->getCollection();

        return response()->json($paginator);
    }

    public function showOneProperty($id)
    {
        return response()->json(Properties::find($id));
    }

    /**
     * Get a detail property.
     * @param  int  $id Require a property id
     * @return A property all information
     */
    public function propertyDetail($id, Request $request = null)
    {
        $user = Auth::user();
        $responder = new ResponderController;

        $getProperty = Properties::with(['amenities', 'currency', 'residence', 'propertyPhotos'])
                ->with([
                    'users' => function ($q) {
                        $q->with('role');
                    }, ])
                ->withCount([// get numnber of favorit user from the pivot table
                    'favorites as favorited' => function ($query) use ($user) {
                        $query->where('users_id', $user->id); //condition on user id getting from favorite table
                    },
                ])
                ->withCount([
                    'request_viewing as request_viewing' => function ($query) use ($user) {
                        $query->where('users_id', $user->id); //condition on user id getting from favorite table
                    },
                ])
                ->where('properties.id', $id)
                ->first();

        if (! $getProperty) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        $getProperty->makeHidden('pro_photos');
        $getProperty->makeHidden('pro_plan');

//        if (!is_null($getProperty['project_name_id'])):
//            $getProperty->with('project_name');
//        endif;
//        dd($getProperty->project_name()->first());

        $propertyDetail = $getProperty;

        if ($getProperty == null) :
            return $responder->returnMessage(0, 'Property', 5);
        endif;
//      =======================Update property user view counter ====================================
        $getProperty->pro_view_counter++;
        $getProperty->timestamps = false;
        $getProperty->save();
//      =============================================================================================
        $photos = [];
        foreach (json_decode($getProperty->pro_photos) as $key) {
            array_push($photos, env('APP_URL').'uploads/property_images/'.$key);
        }

//        ==================Get distance from current location=================================
        $params = $request->query();
        if (isset($params['@'])) {
            $getDistance = $getProperty->getDistance($id, $params['@']);
            $propertyDetail['distance_in_km'] = $getDistance;
        }

//        ======================Photo list ==================================
        $propertyDetail['photos'] = $photos;
        $plans = [];
        if ($getProperty->pro_plan != null) {
            foreach (json_decode($getProperty->pro_plan) as $key) {
                array_push($plans, env('APP_URL').'uploads/property_plan_images/'.$key);
            }
        }
        $propertyDetail['plans'] = $plans;
        $propertyDetail['project_name'] = json_decode('{}');

        if ($propertyDetail['project_name_id'] != null) {
            $project_name = $propertyDetail->project_name()->first();
            $propertyDetail['project_name'] = $project_name;
        }
        //=========================================================================
        $array_resutl = [];
        $array_resutl['property_detial'] = $propertyDetail;

        //================Get similar data=================
        $array_resutl['similar_property'] = $this->similarProperty($getProperty);

        $this->setTrending($id); // insert view record to database;

        return $responder->returnMessage(1, null, null, $array_resutl);
    }

    /**
     * Get list of properties that hosting by a login user.
     *
     * @return List of properties  information
     */
    public function similarProperty($property)
    {
        $properties = new Properties();
        $priceRanges = \App\StatisticOfPriceRanges::all();
        $max_price = $min_price = 0;
        foreach ($priceRanges as $priceRang) {
            if ($this->in_range($property->pro_price, $priceRang->min_price, $priceRang->max_price, true)) :
                $min_price = $priceRang->min_price;
            $max_price = $priceRang->max_price;
            endif;
        }
        if ($max_price == 0) {
            $priceRange = new \App\Http\Controllers\StatisticController();
            $newPriceRange = $priceRange->createPriceRange($property->pro_price);
            $max_price = $newPriceRange->max_price;
            $min_price = $newPriceRange->min_price;
        }
        $distance = 10; //  get property arount 5km
        $selectColumns = ['properties.id', 'pro_public_id',
            'pro_title',
            'pro_use_id',
            'pro_rating',
            'pro_price',
            'pro_currency',
            'pro_floor',
            'pro_lat', 'pro_lng',
            'pro_residence', 'pro_thumbnail',
            'pro_search_type',
        ];
        $arraySelectColumn = implode(',', $selectColumns);

        $propertiesNearby = $this->getNearBy($property->pro_lat, $property->pro_lng, $distance, $arraySelectColumn, $property->id);

        $nearByList = [];
        if (! empty($propertiesNearby)) {
            foreach ($propertiesNearby as $property) :
                array_push($nearByList, $property->id);
            endforeach;
        }

        $similarList = $properties->with(['currency', 'residence', 'amenities', 'users'])
//                        ->where(function ($query) use ($min_price, $max_price) {
//                            $query->whereBetween('pro_price', [$min_price, $max_price]);
//                        })
                        ->where('pro_residence', $property->pro_residence)
                        ->whereIn('pro_search_type', [$property->pro_search_type, 4])
                        ->whereIn('id', $nearByList)
                        ->paginate(self::$number_per_page)->except($property->id);

        if (! empty($similarList)) {
            return $similarList;
        }

        return [];
    }

    public function getNearBy($lat, $lng, $distance, $arraySelectColumn, $id)
    {
        return Properties::getPropertyByDistance($lat, $lng, $distance, $arraySelectColumn)->get()->except($id);
    }

    /**
     * Determines if $number is between $min and $max.
     *
     * @param  int  $number     The number to test
     * @param  int  $min        The minimum value in the range
     * @param  int  $max        The maximum value in the range
     * @param  bool  $inclusive  Whether the range should be inclusive or not
     * @return bool              Whether the number was in the range
     */
    public function in_range($number, $min, $max, $inclusive = false)
    {
        if (is_int($number) && is_int($min) && is_int($max)) {
            return $inclusive ? ($number >= $min && $number <= $max) : ($number > $min && $number < $max);
        }

        return false;
    }

//      public function similarProperty001($property) {
//        $selectColumn = ['properties.id', 'pro_currency', 'pro_public_id', 'pro_title', 'pro_rating', 'pro_floor',
//            'pro_address', 'pro_price', 'pro_lat', 'pro_lng', 'pro_residence', 'pro_use_id',
    ////            'pro_photos',
//            'pro_bed_rooms', 'pro_bath_rooms', 'pro_square_feet', 'created_at', 'pro_thumbnail', 'pro_search_type'];
//        $properties = new Properties();
//        $photoObject = $properties->select($selectColumn)
//                        ->with(["currency", "residence", 'amenities', 'users'])
//                        ->orWhere(function ($query) use ($property) {
//                            $query->whereBetween('pro_price', [$property->pro_price - 50, $property->pro_price + 50]);
//                        })
//                        ->orWhere('pro_city', 'LIKE', '%' . $property->pro_city . '%')
//                        ->where('pro_residence', $property->pro_residence)
    ////                        ->where('pro_lng','LIKE', $property->pro_lng)
    ////                        ->where('pro_lat','LIKE', $property->pro_lat)
//                        ->paginate(self::$number_per_page)->except($property->id);
//
//        if ($photoObject) {
//
//            return $photoObject;
//        }
//        return [];
//    }

    public function propertyByFavorite()
    {
        $responder = new ResponderController;
        $user = Auth::user();
        $properties = Properties::withoutGlobalScopes([\App\Scopes\PropertyScope::class])
                ->with(['currency', 'residence', 'project_name'])
                ->whereHas(
                'favorites', function ($query) use ($user) {
                    $query->where('users_id', $user->id);
                });

        try {
            $getProperty = $properties->paginate(self::$number_per_page);
            if ($getProperty != null) :
                $propertList = [];
            foreach ($getProperty as $property) {
                $setProperty = $property;
                if ($property->users == null) :
                        $setProperty->pro_active = 0;
                endif;

                array_push($propertList, $setProperty);
            }

            return $responder->returnMessage(1, null, null, $propertList); else :
                return $responder->returnMessage(0, 'Property', 5);
            endif;
        } catch (\Exception $e) {
            return $responder->returnMessage(0, 'Property', 5);
        }
    }

    public function propertyByFavoriteWeb(Request $request)
    {
        $responder = new ResponderController;
        $user = Auth::user();
        $properties = Properties::withoutGlobalScopes([\App\Scopes\PropertyScope::class])
                ->with(['currency', 'residence', 'project_name'])
                ->whereHas(
                'favorites', function ($query) use ($user) {
                    $query->where('users_id', $user->id);
                });

        //        ==================Sort data==========================
        $params = $request->query();
        if (isset($params['sort'])) :
            $sortParams = [
                'date_asc' => ['created_at' => 'asc'],
                'date_desc' => ['created_at' => 'desc'],
                'price_asc' => ['pro_price' => 'asc'],
                'price_desc' => ['pro_price' => 'desc'],
            ];
        if (array_key_exists($params['sort'], $sortParams)) :
                $sortBy = $sortParams[$params['sort']];
        foreach ($sortBy as $key => $value) :
                    $properties->orderBy('properties.'.$key, $value);
        endforeach;
        endif;
        endif;
//        ========================================================
        try {
            $getProperty = $properties->paginate(self::$number_per_page);
            if ($getProperty != null) :
                $propertList = [];
            foreach ($getProperty as $property) {
                $setProperty = $property;
                if ($property->users == null) :
                        $setProperty->pro_active = 0;
                endif;

                array_push($propertList, $setProperty);
            }

            return $responder->returnMessage(1, null, null, $propertList); else :
                return $responder->returnMessage(0, 'Property', 5);
            endif;
        } catch (\Exception $e) {
            return $responder->returnMessage(0, 'Property', 5);
        }
    }

    /**
     * Get list of properties that hosting by a login user.
     *
     * @return List of properties  information
     */
    public function postedPropertyByUser()
    {
        $user = Auth::user();
        $responder = new ResponderController;

        $selectColumn = [
            'properties.id',
            'pro_public_id',
            'pro_currency',
            'pro_title',
            'pro_rating',
            'pro_floor',
            'pro_address',
            'pro_price',
            'pro_lat',
            'pro_lng',
            'pro_thumbnail',
            'pro_search_type',
            'pro_residence',
            'pro_use_id',
            'pro_active',
            'pro_bed_rooms',
            'pro_bath_rooms',
            'pro_square_feet',
            'created_at', ];

        $getProperty = Properties::select($selectColumn)->with(['currency', 'residence'])
                        ->where('properties.pro_use_id', $user->id)
                        ->withoutGlobalScopes([\App\Scopes\PropertyScope::class])
                        ->latest()
                        ->paginate(self::$number_per_page)->toArray();

        if (empty($getProperty['data'])) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        return $responder->returnMessage(1, null, null, $getProperty['data']);
    }

    /**
     * Get list of properties that hosting by a login user by web.
     *
     * @return List of properties  information
     */
    public function postedPropertyByUserWeb()
    {
        $user = Auth::user();
        $responder = new ResponderController;

        $selectColumn = [
            'properties.id',
            'pro_public_id',
            'pro_currency',
            'pro_title',
            'pro_rating',
            'pro_floor',
            'pro_address',
            'pro_price',
            'pro_lat',
            'pro_lng',
            'pro_thumbnail',
            'pro_search_type',
            'pro_residence',
            'pro_use_id',
            'pro_active',
            'pro_bed_rooms',
            'pro_bath_rooms',
            'pro_square_feet',
            'created_at', ];

        $getProperty = Properties::select($selectColumn)->with(['currency', 'residence'])
                        ->where('properties.pro_use_id', $user->id)
                        ->withoutGlobalScopes([\App\Scopes\PropertyScope::class])
                        ->latest()
                        ->paginate(self::$number_per_page)->toArray();
        $total_properties = Properties::where('properties.pro_use_id', $user->id)->count();
        //dd($total_properties);

        if (empty($getProperty['data'])) {
            return $responder->returnMessage(0, 'Property', 5);
        }
        $data['properties'] = $getProperty['data'];
        $data['total_property'] = $total_properties;

        return $responder->returnMessage(1, null, null, $data);
    }

    /**
     * Get list of properties that hosting by id of posted user.
     *
     * @return List of properties  information
     */
    public function propertyByUserId($id)
    {
        $responder = new ResponderController;
        $selectColumn = [
            'properties.id',
            'pro_public_id',
            'pro_currency',
            'pro_title',
            'pro_rating',
            'pro_floor',
            'pro_address',
            'pro_price',
            'pro_lat',
            'pro_lng',
            'pro_thumbnail',
            'pro_search_type',
            'pro_residence',
            'pro_use_id',
            'pro_active',
            'pro_bed_rooms',
            'pro_bath_rooms',
            'pro_square_feet',
            'favorites_count',
            'project_name_id',
            'created_at', ];

        $propertyByLastUpdate = Properties::select($selectColumn)->with(['currency', 'residence', 'project_name'])
                        ->where('pro_use_id', $id)
                        ->latest()
                        ->paginate(self::$number_per_page)->toArray();

        //==============Check exist user info==============
        if (empty($propertyByLastUpdate['data'])) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        $getProperty['last_update'] = $propertyByLastUpdate['data'];

        $propertyByFavorite = Properties::select($selectColumn)->with(['currency', 'residence', 'project_name'])
                        ->where('pro_use_id', $id)
                        ->orderBy('favorites_count', 'DESC')
                        ->paginate(self::$number_per_page)->toArray();
        $getProperty['mose_favorite'] = $propertyByFavorite['data'];

        return $responder->returnMessage(1, null, null, $getProperty);
    }

    public function generatePropertyList($properties)
    {
        $getArrayPropreties = [];
        foreach ($properties as $getProperty) {
            $property = $getProperty;
            array_push($getArrayPropreties, $property);
        }

        return $getArrayPropreties;
    }

    /**
     * Toggle favorite property.
     */
    public function toggleFavorite($id)
    {
        $user = Auth::user();
        $property = Properties::find($id);
        if ($property == null) :
            return $this->getResponseData('0', 'Property not found.', '');
        endif;
        $resutl = $property->favorites()->toggle($user->id);

        ///=============Update property favorite count=============
        if (count($resutl['attached']) > 0) :
            $property->favorites_count++; else :
            $property->favorites_count = $property->favorites_count - 1;
        endif;
        $property->timestamps = false;
        $property->save();

        return $this->getResponseData('1', 'Favorite property have been update successfully', '');
    }

    /**
     * Add property to a favorite list by login user.
     * @param Request $data user id and property id
     * @return bolean
     */
    public function addFavorite(Request $request)
    {
        return $this->toggleFavorite($request->input('property_id'));
    }

//
//    /**
//     * Remove property from a favorite list by login user
//     * @param Request $data user id and property id
//     * @return bolean
//     */
    public function removeFavorite($id)
    {
        return $this->toggleFavorite($id);
    }

    /**
     * List property near by user location.
     * @param Request $data GO location lng & lat
     * @return array list of property
     */
    public function getFeatureAndNearby(Request $request)
    {
        $responder = new ResponderController;
        $selectColumn = [
            'properties.id',
            'pro_public_id',
            'pro_title',
            'pro_use_id',
            'pro_rating',
            'pro_price',
            'pro_currency',
            'pro_floor',
            'pro_lat',
            'pro_lng',
            'pro_address',
            'pro_bed_rooms',
            'pro_bath_rooms',
            'pro_residence',
            'pro_thumbnail',
            'pro_search_type',
            'created_at',
            'updated_at',
            'favorites_count',
            'project_name_id',
        ];
        $returnResult['feature'] = $this->showFeatured($request, $selectColumn);
        //===============Get nearest property================;
        $returnResult['nearby'] = $this->getNearest($request, $selectColumn);

        // ========================Get last update property======================
        $returnResult['lastUpdate'] = $this->showLastUpdate($request, $selectColumn);

        // ========================Get property have video======================
        $returnResult['videos'] = $this->showVideos($selectColumn);

//        ========================= Advertisement===================================
        $advertise = new AdvertisementController;
        $returnResult['advertisement'] = $advertise->index();

        return $responder->returnMessage(1, null, null, $returnResult);
    }

    private function getNearest($request, $selectColumns)
    {
        $distance = 10; //Set defauld 10 km
        if ($request->has('distance')) {
            $distance = $request->input('distance');
        }

        $validator = Validator::make($request->all(), ['lng' => 'required', 'lat' => 'required']);

        if ($validator->fails()) : // check user input
            return [];
        endif;

        $lat = $request->input('lat');
        $lng = $request->input('lng');

        $arraySelectColumn = implode(',', $selectColumns);
        $query = Properties::getPropertyByDistance($lat, $lng, $distance, $arraySelectColumn)->paginate(self::$number_per_page)->toArray();
        if (empty($query)) {
            return [];
        }

        return $query['data'];
    }

    /**
     * Get near by location property.
     * @param  array $request user Id,lat & lng
     * @return list of property with the last most view
     *///localhost/lumenapi/api/featured

    public function mapCluster(Request $request)
    {
        $selectColumns = [
            'id',
            'pro_currency',
            'pro_title',
            'pro_public_id',
            'pro_rating',
            'pro_address',
            'pro_price',
            'pro_lat',
            'pro_lng',
            'pro_photos',
            'pro_residence',
            'pro_use_id',
            'pro_bed_rooms',
            'pro_bath_rooms',
            'pro_square_feet',
            'created_at',
            'favorites_count',
            'pro_thumbnail', ];

        if ($request->has('polygon')) {
            $property = $this->getPolygonData($request, $selectColumns);
        } else {
            $property = $this->getNearest($request, $selectColumns);
        }

        if (empty($property)) {
            return $this->getResponseData('1', 'Note property found.', $property);
        }
        try {
            return $this->getResponseData('1', '', $property);
        } catch (\Exception $e) {
            return $this->getResponseData('0', 'Note property found.', $property);
        }
    }

    public function getPolygonData($request, $selectColumns)
    {
        $arraySelectColumn = implode(',', $selectColumns);
        $strPolygon = "'".implode(',', $request->polygon).','.$request->polygon[0]."'";
        $propertyList = Properties::getPropertyByPolygon($strPolygon, $arraySelectColumn);

        if (empty($propertyList)) {
            return [];
        }
        $ids = [];
        //Extract the id's
        foreach ($propertyList as $q) {
            array_push($ids, $q->id);
        }
        // Get the listings that match the returned ids
        $getProperty = Properties::select($selectColumns)
                        ->with('currency', 'residence')
                        ->whereIn('id', $ids)->orderBy('pro_rating', 'DESC')->get();

        return $this->generatePropertyList($getProperty);
    }

    public function mapCluster001(Request $request)
    {
        $responder = new ResponderController;
        $selectColumn = ['properties.id', 'pro_title', 'pro_lat', 'pro_lng', 'res_title'];
        $selectColumn = "'".implode(',', $selectColumn)."'";
        try {
            $returnResult = $this->showNearByProperty($request, $selectColumn);

            return $responder->returnMessage(1, null, '', $returnResult);
        } catch (\Illuminate\Database\QueryException $e) {
            return $responder->returnMessage(0, 'Property', 1);
        }
    }

    /**
     * Get feature of trending property.
     * @param
     * @return list of property with the last most view
     *///localhost/lumenapi/api/featured

    private function showFeatured($request, $selectColumns)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $arraySelectColumn = implode(',', $selectColumns);
//        $featureProperties = Trendings::getTrendingId(self::$number_per_page, $selectColumns);
        //$featureProperties = Trendings::getTrendingIdWithDistance(self::$number_per_page);
        //$propertyList = Properties::getPropertyDistanceByCurrenctLocation($lat, $lng, $arraySelectColumn, $featureProperties)->toArray();
        $properties = Properties::select('properties.id')
                             ->orderBy('pro_view_counter', 'DESC')
                             ->paginate(self::$number_per_page)->toArray();
        $propertyList = [];
        foreach ($properties['data'] as $property) {
            if ($property['id'] != null) {
                array_push($propertyList, $property['id']);
            }
        }

        $featureProperties = Properties::getLatestViewProperties($arraySelectColumn, $propertyList);

        if (empty($featureProperties)) {
            return [];
        }

        return $featureProperties;
    }

    /**
     * Get video of property.
     * @param
     * @return list of property with the last most view
     */
    private function showVideos($selectColumns)
    {
        array_push($selectColumns, 'pro_videos as video_id');

        $videos = Properties::select($selectColumns)
                        ->with('currency')
                        ->where('pro_videos', '<>', '')
                        ->paginate(self::$number_per_page)->toArray();

        if (empty($videos)) {
            return [];
        }

        return $videos['data'];
    }

    /**
     * Get last update of property.
     * @param
     * @return list of property with the last most view
     *///localhost/lumenapi/api/featured

    private function showLastUpdate($request, $selectColumns)
    {
        $lastUpdate = Properties::select('id')
                        ->orderBy('updated_at', 'desc')
                        ->paginate(self::$number_per_page)->toArray();
        $lastUpdateId = [];
        foreach ($lastUpdate['data'] as $property) {
            array_push($lastUpdateId, $property['id']);
        }
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $arraySelectColumn = implode(',', $selectColumns);

        $propertyList = Properties::getPropertyDistanceByCurrenctLocation($lat, $lng, $arraySelectColumn, $lastUpdateId)->toArray();

        if (empty($propertyList)) {
            return [];
        }

        return $propertyList;
    }

    /**
     * Create new property with validation and authentication check.
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function hosting(Request $request, Properties $properties)
    {
        $user = Auth::user();
        $responder = new ResponderController;
        $validator = $this->hostingValidation($request);
        $getData = $request->all();
        if ($validator->fails()) : // check user input
            return $this->getResponseData('0', $validator->errors()->first(), '');
        endif;

        $fileUploadLimited = $this->limitedFileUpload($request->file('photos'), 3, 15);
        if (! $fileUploadLimited['status']) {
            return $fileUploadLimited['message'];
        }

        if ($request->hasFile('plan')) :
            $fileUploadLimited = $this->limitedFileUpload($request->file('plan'), 0, 5);
        if (! $fileUploadLimited['status']) {
            return $fileUploadLimited['message'];
        }
        endif;

        //========================upload video to Youtube server================
        if ($request->hasFile('video')) {
            $youtubeUpload = new YoutubeController();
            $videoUploaded = $youtubeUpload->youtubeVideoUpload($request, $request->input('pro_title'));
            if (! $videoUploaded['status']) {
                return $this->getResponseData('0', 'Video upload Error', $videoUploaded['message']);
            }
            $getVideoUploaded = $videoUploaded['data'];
            $getData['pro_videos'] = $getVideoUploaded->id;
        }

        ///==================Do file upload and return the file name===============================
        try {
            $photosName = $this->doFileUpload($request, 'photos', null, true);
            if ($request->hasFile('plan')) {
                $planName = $this->doFileUpload($request, 'plan', self::$property_plan_path);
                $getData['pro_plan'] = json_encode($planName);
            } else {
                $getData['pro_plan'] = null;
            }
        } catch (\Exception $e) {
            return $this->getResponseData('0', 'File upload Error', $e->getMessage());
        }

        $getData['pro_thumbnail'] = $photosName['thumbnail'];

        $getData['pro_photos'] = json_encode($photosName['photos']);
        $getData['pro_use_id'] = $user->id;

        if ($request->has('pro_residence') && $getData['pro_residence'] == 11) : // Land for sale or rend
            $getData['pro_price'] = $getData['unite_price'] * $getData['pro_square_feet'];
        endif;

        //=================residence + property type + price==============================
//        $residence = Residence::find($request->input('pro_residence'))->first();
//        $propertyType = \App\PropertyType::find($request->input('pro_search_type'))->first();
//        $pro_title = $residence->res_title . $propertyType->title . " $" . $getData['pro_price'];
//        $getData['pro_title'] = $pro_title;
        //===============================================================================
        //dd($getData);
        try {
            $propertyList = $properties->create($getData);
            if ($request->input('pro_amenities')) {
                $this->addAmenities($propertyList, $request->input('pro_amenities'));
            }

            return $responder->returnMessage(1, 'Property', 2, $propertyList);
        } catch (\Exception $e) {
            return $responder->returnMessage(0, 'Property', 1, $e->getMessage());
        }
    }

    /**
     * Create new property with validation and authentication check.
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function hostingByWeb(Request $request)
    {
        $user = Auth::user();
        $responder = new ResponderController;
        $rules = [
            'pro_price' => 'required|numeric|digits_between:1,11',
            'pro_lng' => 'numeric',
            'pro_lat' => 'numeric',
            'pro_residence' => 'required|numeric|exists:residences,id',
            'pro_search_type' => 'required|numeric|exists:property_type,id',
            'pro_detail' => 'max:1000',
            'pro_city' => 'regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_state' => 'regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_status' => 'numeric',
            'pro_zip' => 'alpha_num',
            'pro_age' => 'numeric|max:100',
            'videos' => 'mimes:mp4,mov,ogg,qt | max:200000',
            'photoUrls' => 'required|array|between:3,15',
        ];

        if ($request->input('planUrls')) {
            $rules['planUrls'] = 'array|between:0,5';
        }
        $validator = Validator::make($request->all(), $rules, $this->setUserValidationMessage());

        if ($validator->fails()) : // check user input
            return $this->getResponseData('0', $validator->errors()->first(), '');
        endif;

        $getData = $request->all();

        //========================upload video to Youtube server================
        if ($request->hasFile('video')) {
            $youtubeUpload = new YoutubeController();
            $videoUploaded = $youtubeUpload->youtubeVideoUpload($request, $request->input('pro_title'));
            if (! $videoUploaded['status']) {
                return $this->getResponseData('0', 'Video upload Error', $videoUploaded['message']);
            }
            $getVideoUploaded = $videoUploaded['data'];
            $getData['pro_videos'] = $getVideoUploaded->id;
        }

        ///==================Do file upload and return the file name===============================
        try {
            $photosName = $this->urlFilesUpload($request->input('photoUrls'), 'photos', null, true);
            $getData['pro_photos'] = json_encode($photosName['photos']);
            $getData['pro_thumbnail'] = $photosName['thumbnail'];

            if ($request->has('planUrls')) {
                $planName = $this->urlFilesUpload($request->input('planUrls'), 'plan', self::$property_plan_path);
                $getData['pro_plan'] = json_encode($planName['plan']);
            } else {
                $getData['pro_plan'] = null;
            }
        } catch (\Exception $e) {
            return $this->getResponseData('0', 'File upload faild.', $e->getMessage());
        }

        $getData['pro_use_id'] = $user->id;

        //=================residence + property type + price==============================
        $residence = Residence::find($request->input('pro_residence'))->first();
        $propertyType = \App\PropertyType::find($request->input('pro_search_type'))->first();
        $pro_title = $residence->res_title.' for '.$propertyType->title.' $'.$getData['pro_price'];
        $getData['pro_title'] = $pro_title;
        //===============================================================================
//
        try {
            $propertyList = Properties::create($getData);
            if ($request->input('pro_amenities')) {
                $this->addAmenities($propertyList, $request->input('pro_amenities'));
            }

            return $responder->returnMessage(1, 'Property', 2, $propertyList);
        } catch (\Exception $e) {
            return $responder->returnMessage(0, 'Property', 1, $e->getMessage());
        }
    }

    /**
     * Get a validate for each input with customize message.
     *
     * @param  array  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function hostingValidation($request)
    {
        $rules = [
//            'pro_title' => 'min:5|regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_price' => 'required|numeric|digits_between:1,11',
            'pro_lng' => 'numeric',
            'pro_lat' => 'numeric',
            'pro_residence' => 'required|numeric|exists:residences,id',
            'pro_search_type' => 'required|numeric|exists:property_type,id',
            'project_name_id' => 'numeric|exists:project_name,id',
            'pro_detail' => 'max:1000',
            'pro_city' => 'regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_state' => 'regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_status' => 'numeric',
            'pro_zip' => 'alpha_num',
            'pro_age' => 'numeric|max:100',
            'videos' => 'mimes:mp4,mov,ogg,qt | max:200000',
        ];

        $rules = $this->photoValidation($rules, $request);

        if ($request->hasFile('plan')) {
            $rules = $this->planValidation($rules, $request);
        }

        return Validator::make($request->all(), $rules, $this->setUserValidationMessage());
    }

    /**
     * List error message support multi language.
     * @return array list of error message for each input
     */
    public function setUserValidationMessage()
    {
        $errorMessage = [
            'username.required' => 'A user name is required',
            'username.min' => 'The username must be at least 5 characters',
            'password.required' => 'A user password is required',
        ];

        return $errorMessage;
    }

    /**
     * Function to check multi file upload.
     * @param  array $staticRules list input to validate
     * @param  array $data requested data
     * @param  string $strFileName input name that concent the file
     * @return array List of file name from generate number and time
     */
    public function photoValidation($staticRules, $data)
    {
        $nbr = count($data->file('photos'));
        foreach (range(0, $nbr - 1) as $index) :
            $staticRules['photos'.'.'.$index] = 'image|mimes:jpeg,png,jpg,gif,pdf,tiff,svg,ai,psd,heic'; //it's size is smaller or equal to 5120 kb
        endforeach;

        return $staticRules;
    }

    /**
     * Function to check multi file upload.
     * @param  array $staticRules list input to validate
     * @param  array $data requested data
     * @param  string $strFileName input name that concent the file
     * @return array List of file name from generate number and time
     */
    public function planValidation($staticRules, $data)
    {
        $nbr = count($data->file('plan'));
        foreach (range(0, $nbr - 1) as $index) :
            $staticRules['plan'.'.'.$index] = 'image|mimes:jpeg,png,jpg,gif,pdf,tiff,svg,ai,psd,heic'; //it's size is smaller or equal to 5120 kb
        endforeach;

        return $staticRules;
    }

    public function planUrlsValidation($staticRules, $data)
    {
        $nbr = count($data->file('plan'));
        foreach (range(0, $nbr - 1) as $index) :
            $staticRules['plan'.'.'.$index] = 'image|mimes:jpeg,png,jpg,gif,pdf,tiff,svg,ai,psd,heic'; //it's size is smaller or equal to 5120 kb
        endforeach;

        return $staticRules;
    }

    public function addAmenities(Properties $property, $amenity_id_array)
    {
        if (! is_array($amenity_id_array)) {
            return false;
        }

        $property->amenities()->attach($amenity_id_array);

        return true;
    }

    public function updateAmenities(Properties $property, $request)
    {
        return $property->amenities()->sync($request);
    }

    public function getArrayForInput($feild)
    {
        $explode = str_replace('[', '', preg_split('[,]', $feild));

        return str_replace(']', '', $explode);
    }

    public function update(Request $request, $propertyId)
    {
        $property = Properties::find($propertyId)->first();
        $responder = new ResponderController;
        if ($property == null) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        if (! $request->all()) {
            return $responder->returnMessage(0, null, null, '', 'Nothing to update.');
        }

        $validator = $this->requestValidator($request);
        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        $property->update($request->all());

        return $responder->returnMessage(1, 'Property', 2, $property);
    }

    /*
     * Update video on Youtube, Auth user only can perfor this task
     * @param $videoId
     * @return
     */

    public function updateVideo($id, Request $request)
    {
        $property = Properties::find($id);
        $responder = new ResponderController;
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }
        $validator = Validator::make($request->all(), ['video' => 'required']);

        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        //===============Check existing video ===============================
        $youtubeUpload = new YoutubeController();
        if ($property->pro_videos == null) : /// Don't have video, upload a new one
            $videoUploaded = $youtubeUpload->youtubeVideoUpload($request, $request->input('video'), $request->input('pro_title')); else :
            // Remove and update video on Youtube server
            $videoUploaded = $youtubeUpload->youtubeVideoUpdate($request, $request->file('video'), $property->pro_videos);

        endif;
        if (! $videoUploaded['status']) {
            return $this->getResponseData('0', 'Video upload failed. ', $videoUploaded['message']);
        }
        $getVideoUploaded = $videoUploaded['data'];
        if (! $property->update(['pro_videos' => $getVideoUploaded->id])) :
            return $responder->returnMessage(0, 'Property', 1);
        endif;

        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
    }

    /**
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function deleteVideo($id, Request $request)
    {
        $property = Properties::find($id);
        $responder = new ResponderController;
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }
        $validator = Validator::make($request->all(), ['video' => 'required']);

        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, '', $validator->errors()->first());
        }

        //===============Check existing video ===============================
        $youtubeUpload = new YoutubeController();
        // Remove and update video on Youtube server
        $videoUploaded = $youtubeUpload->youtubeVideoDelete($request, $request->input('video'));

        if (! $videoUploaded['status']) {
            return $this->getResponseData('0', $videoUploaded['message'], '');
        }
        if (! $property->update(['pro_videos' => ''])) :
            return $responder->returnMessage(0, 'Property', 1);
        endif;

        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
    }

    public function updateByWeb(Request $request, $propertyId)
    {
        $property = Properties::find($propertyId);
        $responder = new ResponderController;

        if ($property == null) {
            return $responder->returnMessage(0, 'Property', 5);
        }
        if (! $request->all()) {
            return $responder->returnMessage(0, null, null, '', 'Nothing to update.');
        }
        $validator = $this->requestValidatorByWeb($request);
        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        //================Update property amenities=======================
        if ($request->has('pro_amenities')) :
            $property->amenities()->sync($request->input('pro_amenities'));
        endif;

        if (! $request->has('pro_amenities')) {
            DB::table('amenity_property')->where('property_id', $propertyId)->delete();
        }
        //===============================================================
        if ($request->has('photoUrls')) :
            $oldPhotos = json_decode($property->pro_photos);

        $photoUpdate = $this->filterFileUploadWithUrl($request->input('photoUrls'), $oldPhotos, 'photos', self::$property_photos_path, true);

        if (! is_array($photoUpdate)) :
                return $photoUpdate;
        endif;

        if ($photoUpdate['thumbnail']) :
                $property->pro_thumbnail = $photoUpdate['thumbnail'];
        endif;

        $property->pro_photos = json_encode($photoUpdate['photos']);

        endif;

        if ($request->has('planUrls')) :

            if (count($property->pro_plan) > 0) :
                $oldPlans = json_decode($property->pro_plan);
        $planUpdate = $this->filterFileUploadWithUrl($request->input('planUrls'), $oldPlans, 'plan', self::$property_plan_path);
        if (! is_array($planUpdate)) :
                    return $planUpdate;
        endif;

        $property->pro_plan = json_encode($planUpdate['plan']); else :
                $planName = $this->urlFilesUpload($request->input('planUrls'), 'plan', self::$property_plan_path);
        $property->pro_plan = json_encode($planName['plan']);
        endif;

        endif;

        if ($request->hasFile('video')) {

//===============Check existing video ===============================
            $youtubeUpload = new YoutubeController();
            if ($property->pro_videos == null) : /// Don't have video, upload a new one
                $videoUploaded = $youtubeUpload->youtubeVideoUpload($request, $request->file('video'), $request->input('pro_title')); else :
                // Remove and update video on Youtube server
                $videoUploaded = $youtubeUpload->youtubeVideoUpdate($request, $request->file('video'), $property->pro_videos);

            endif;
            if (! $videoUploaded['status']) {
                return $this->getResponseData('0', 'Video upload failed. ', $videoUploaded['message']);
            }
            $getVideoUploaded = $videoUploaded['data'];
            $property->pro_videos = $getVideoUploaded->id;
        }
        if (! $property->update($request->all())) :
            return $responder->returnMessage(0, 'Property', $property);
        endif;

        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
    }

    /*
     * Update property search type and price
     */

    public function updatePrice($id, Request $request)
    {
        $property = Properties::find($id);
        $responder = new ResponderController;
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }
        $rules = [
            'pro_price' => 'required|numeric|digits_between:1,11',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        if (! $property->update($request->all())) :
            return $responder->returnMessage(0, 'Property', 1);
        endif;

        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
    }

//    public function updatePrice($id, Request $request) {
//
//        $property = Properties::find($id);
//        $responder = new ResponderController;
//        if (!$property) {
//            return $responder->returnMessage(0, 'Property', 5);
//        }
//
//        $rules = [
//            'pro_price' => 'required|numeric|digits_between:1,11',
//            'pro_search_type' => 'required|numeric|exists:property_type,id',
//            'pro_square_feet' => 'required|numeric|digits_between:1,11',
//        ];
//
//        $validator = Validator::make($request->all(), $rules);
//
//        if ($validator->fails()) {
//            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
//        }
//
//        if (!$property->update($request->all())):
//            return $responder->returnMessage(0, 'Property', 1);
//        endif;
//
//        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
//    }

    /**
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function updateMoreInfo($id, Request $request)
    {
        $property = Properties::find($id);
        $responder = new ResponderController;
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        $rules = [
            'pro_status' => 'required|numeric|exists:property_status,id',
            'pro_age' => 'numeric|exists:age_category,id',
            'pro_detail' => 'max:2500',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        if (! $property->update($request->all())) :
            return $responder->returnMessage(0, 'Property', $property);
        endif;

        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
    }

    /**
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function updateSearchType($id, Request $request)
    {
        $property = Properties::find($id);
        $responder = new ResponderController;
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        $rules = [
            'pro_search_type' => 'numeric|exists:property_type,id',
            'pro_square_feet' => 'numeric|digits_between:1,10',
            'pro_price' => 'required|numeric|digits_between:1,11',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        if (! $property->update($request->all())) :
            return $responder->returnMessage(0, 'Property', $property);
        endif;

        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
    }

    /**
     * For ios old UI.
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function updateMoreInfoIos($id, Request $request)
    {
        $property = Properties::find($id);
        $responder = new ResponderController;
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        $rules = [
            'pro_detail' => 'max:1000',
            'pro_status' => 'numeric|exists:property_status,id',
            'pro_age' => 'numeric|exists:age_category,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        if (! $property->update($request->all())) :
            return $responder->returnMessage(0, 'Property', 1);
        endif;

        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
    }

    /*
     * Update property contact
     */

    public function updateContact($id, Request $request)
    {
        $property = Properties::find($id);
        $responder = new ResponderController;
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        $rules = [
            'username' => 'max:50',
            'phone' => 'numeric|digits_between:9,12',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        $user = Auth::user();
        $user->fill($request->input());
        if (! $user->save()) :
            return $responder->returnMessage(0, 'User', 1);
        endif;

        return $responder->returnMessage(1, 'User', 2, $property->fresh());
    }

    /*
     * Update property residenc and amenity
     * {{server-address}}property/update/amenity/{id}
     *
     */

    public function updateResidenceAndAmenity($id, Request $request)
    {
        $property = Properties::find($id);
        $responder = new ResponderController;
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        $rules = ['pro_amenities' => 'array',
            'pro_residence' => 'required|numeric|exists:residences,id',
            'pro_amenities.*' => 'required|distinct|numeric',
            'pro_floor' => 'numeric|digits_between:1,3',
            'pro_bed_rooms' => 'numeric|digits_between:1,3',
            'pro_bath_rooms' => 'numeric|digits_between:1,3',
            'pro_parking' => 'numeric|digits_between:1,3',
            'project_name_id' => 'numeric|nullable|exists:project_name,id',
        ];

        if ($request->has('pro_amenities')) :
            foreach ($request->input('pro_amenities') as $key => $val) :
                $rules['pro_amenities'.'.'.$key] = 'required|exists:amenities,id';
        endforeach;
        endif;

        if ($request->input('pro_amenities') == null) {
            DB::table('amenity_property')->where('property_id', $id)->delete();
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        if (! $property->update($request->all())) :
            return $responder->returnMessage(0, 'Property', $property);
        endif;
        if ($request->has('pro_amenities')) :
            if (! $property->amenities()->sync($request->input('pro_amenities'))) :
                return $responder->returnMessage(0, 'Property', 1);
        endif;
        endif;

        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
    }

    /*
     * Update property location
     * {{server-address}}property/update/location/{id}
     *
     */

    public function updateLocation($id, Request $request)
    {
        $property = Properties::find($id);
        $responder = new ResponderController;
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }

        $rules = [
            'pro_lng' => 'required|numeric',
            'pro_lat' => 'required|numeric',
            'pro_address' => 'max:500',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $responder->returnMessage(0, null, null, [], $validator->errors()->first());
        }

        if (! $property->update($request->all())) :
            return $responder->returnMessage(0, 'Property', $property);
        endif;

        return $responder->returnMessage(1, 'Property', 2, $property->fresh());
    }

    /**
     * Function to create rules for each form request.
     * @param  Request $request list input to validate
     * @return 5.5/Illuminate/Validation/Validator
     */
    public function requestValidator(Request $request)
    {
        $rulesList = [
//            'pro_title' => 'required|min:5|max:100',
//            'pro_price' => 'required|numeric|min:10',
            'pro_address' => 'required|max:500',
            'pro_lng' => 'required|numeric',
            'pro_lat' => 'required|numeric',
            'pro_residence' => 'required|numeric',
            'pro_search_type' => 'required|numeric',
            'pro_floor' => 'required|numeric|max:3',
            'pro_bed_rooms' => 'required|numeric|max:3',
            'pro_bath_rooms' => 'required|numeric|max:3',
            'pro_parking' => 'required|numeric|max:1',
            'pro_square_feet' => 'required|numeric|max:5',
            'pro_detail' => 'required|max:1000',
            'pro_city' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_state' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_zip' => 'required|alpha_num',
            'pro_contact_name' => 'required|max:50',
            'pro_contact_number' => 'required|alpha_num|max:50',
            'pro_contact_email' => 'required|email',
            'pro_age' => 'required|numeric',
        ];
        $rules = [];
        if ($request->has('pro_amenities')) {
            $rules = ['pro_amenities' => 'required|array', 'pro_amenities.*' => 'required|distinct|numeric'];
            $validation = Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                return $validation;
            }

            foreach ($request->input('pro_amenities') as $key => $val) :
                $rules['pro_amenities'.'.'.$key] = 'max:2';
            endforeach;

            return Validator::make($request->all(), $rules);
        }

        foreach ($request->all() as $key => $value) {
            if (isset($rulesList[$key])) {
                $rules[$key] = $rulesList[$key];
            }
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Function to create rules for each form request.
     * @param  Request $request list input to validate
     * @return 5.5/Illuminate/Validation/Validator
     */
    public function requestValidatorByWeb(Request $request)
    {
        $rulesList = [
            'pro_price' => 'required|numeric|digits_between:1,11',
            'pro_address' => 'max:500',
            'pro_lng' => 'required|numeric',
            'pro_lat' => 'required|numeric',
            'pro_residence' => 'required|numeric',
            'pro_search_type' => 'required|numeric',
            'pro_floor' => 'numeric|max:3',
            'pro_bed_rooms' => 'numeric|max:3',
            'pro_bath_rooms' => 'numeric|max:3',
            'pro_parking' => 'numeric|max:1',
            'pro_square_feet' => 'required|numeric',
            'pro_detail' => 'max:1000',
            'pro_city' => 'regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_state' => 'regex:/(^[A-Za-z0-9 ]+$)+/',
            'pro_zip' => 'alpha_num',
            'pro_contact_name' => 'max:50',
            'pro_contact_number' => 'alpha_num|max:50',
            'pro_contact_email' => 'email',
            'pro_age' => 'numeric',
            'videos' => 'mimes:mp4,mov,ogg,qt | max:200000',
            'photoUrls' => 'required|array|between:3,15',
            'planUrls' => 'array|between:3,15',
        ];
        $rules = [];
        if ($request->has('pro_amenities')) {
            $rules = ['pro_amenities' => 'required|array', 'pro_amenities.*' => 'required|distinct|numeric'];
            $validation = Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                return $validation;
            }

            foreach ($request->input('pro_amenities') as $key => $val) :
                $rules['pro_amenities'.'.'.$key] = 'max:2';
            endforeach;

            return Validator::make($request->all(), $rules);
        }

        foreach ($request->all() as $key => $value) {
            if (isset($rulesList[$key])) {
                $rules[$key] = $rulesList[$key];
            }
        }

        return Validator::make($request->all(), $rules);
    }

    public function updatePlan(Request $request, $propertyId)
    {
        $responder = new ResponderController;
//      ============Check property by id ================
        $propertyUpdate = Properties::find($propertyId);
        if (! $propertyUpdate) {
            return $responder->returnMessage(0, 'Property', 5, []);
        }
        $property = DB::table('properties')->where('id', $propertyId)->first();

        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5, []);
        }

//      ====================Validation photos================
        $validator = $this->validatePhotos($request, 'plan');
        if ($validator->fails()) :
            return $responder->returnMessage(0, null, null, '', $validator->errors()->first());
        endif;

        $fileUploadLimited = $this->limitedFileUpload($request->file('plan'), 0, 5);
        if (! $fileUploadLimited['status']) {
            return $fileUploadLimited['message'];
        }
        //============filter array and move file upload=============================
        try {
            $planName = '';
            $oldArray = [];
            if (! empty($property->pro_plan)) {
                $oldArray = json_decode($property->pro_plan);
            }

            $planUpdate = $this->filterFileUpload($request, $oldArray, 'plan', 'uploads/property_plan_images');
            if (! is_array($planUpdate)) :
                return $planUpdate;
            endif;
            $getData['pro_plan'] = json_encode($planUpdate['plan']);

            $propertyUpdate->update($getData);

            return $responder->returnMessage(1, 'Property', 2, $propertyUpdate);
        } catch (Exception $ex) {
            return $responder->returnMessage(0, 'Property', null, $ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param type $propertyId
     * @return type
     */
    public function getUpdateData()
    {
        $ageCategory = \App\AgeCategory::all();
        if (empty($ageCategory)) :
            return $this->getResponseData('0', trans('messages.data_not_found'), '');
        endif;
        $data['pro_age'] = $ageCategory;

        $propertyStatus = \App\PropertyStatus::all();
        if (empty($propertyStatus)) :
            return $this->getResponseData('0', trans('messages.data_not_found'), '');
        endif;

        $data['pro_status'] = $propertyStatus;

        $propertyType = \App\PropertyType::all();
        if (empty($propertyType)) :
            return $this->getResponseData('0', trans('messages.data_not_found'), '');
        endif;

        $data['pro_search_type'] = $propertyType;

        return $this->getResponseData('1', trans('messages.data_update_success'), $data);
    }

    /**
     * @return array List of Age category, property status, property search type,
     */
    public function getHostingData()
    {
        $ageCategory = \App\AgeCategory::all();
        if (empty($ageCategory)) :
            return $this->getResponseData('0', trans('messages.data_not_found'), '');
        endif;
        $data['pro_age'] = $ageCategory;

        $propertyStatus = \App\PropertyStatus::all();
        if (empty($propertyStatus)) :
            return $this->getResponseData('0', trans('messages.data_not_found'), '');
        endif;

        $data['pro_status'] = $propertyStatus;

        $propertyType = \App\PropertyType::all();
        if (empty($propertyType)) :
            return $this->getResponseData('0', trans('messages.data_not_found'), '');
        endif;

        $data['pro_search_type'] = $propertyType;

        return $this->getResponseData('1', '', $data);
    }

    public function updatePhoto(Request $request, $propertyId)
    {
        $responder = new ResponderController;
//      ============Check property by id ================
        $propertyUpdate = Properties::find($propertyId);
        if (! $propertyUpdate) {
            return $responder->returnMessage(0, 'Property', 5, []);
        }
        $property = DB::table('properties')->where('id', $propertyId)->first();

        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5, []);
        }
//      ====================Validation photos================
        $validator = $this->validatePhotos($request, 'photos');
        if ($validator->fails()) :
            return $responder->returnMessage(0, 'Property', 6, $validator->errors()->first());
        endif;

        $fileUploadLimited = $this->limitedFileUpload($request->file('photos'), 3, 15);
        if (! $fileUploadLimited['status']) {
            return $fileUploadLimited['message'];
        }

        //============filter array and move file upload=============================
        try {
            $oldArray = json_decode($property->pro_photos);
            $photoUpdate = $this->filterFileUpload($request, $oldArray, 'photos', 'uploads/property_images', true);

            if (! is_array($photoUpdate)) :
                return $photoUpdate;
            endif;
            if ($photoUpdate['thumbnail']) :
                $getData['pro_thumbnail'] = $photoUpdate['thumbnail'];
            endif;
//        ========================Update database =================================
            $getData['pro_photos'] = json_encode($photoUpdate['photos']);

            $propertyUpdate->update($getData);

            return $responder->returnMessage(1, 'Property', 2, $propertyUpdate);
        } catch (Exception $ex) {
            return $responder->returnMessage(0, 'Property', null, $ex->getMessage());
        }
    }

    public function removeFile($fileName)
    {
        if (file_exists($fileName)) :
            return unlink($fileName);
        endif;

        return false;
    }

//    public function destroy($filename) {
//        try {
//            return \Illuminate\Support\Facades\File::delete($filename);
//        } catch (\Exception $e) {
//            return $e->errorInfo[1];
//        }
//    }

    public function validatePhotos(Request $request, $inputName = null)
    {
        $limit_photos = count($request->file($inputName));
        foreach (range(0, $limit_photos - 1) as $index) :
            $rules[$inputName.'.'.$index] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5000'; //it's size is smaller or equal to 5120 kb
        $rulesMessage[$inputName.'.'.$index.'.required'] = 'Please upload an '.$inputName;
        $rulesMessage[$inputName.'.'.$index.'.image'] = 'File '.($index + 1).' must be an image.';
        $rulesMessage[$inputName.'.'.$index.'.mimes'] = 'Only jpeg,png,jpg,gif and svg images are allowed';
        $rulesMessage[$inputName.'.'.$index.'.max'] = 'Sorry! Maximum allowed size for an image is 5MB';
        endforeach;

        return Validator::make($request->all(), $rules, $rulesMessage);
    }

    /**
     * @param Request $request
     * @param array $oldArray
     * @param string $fieldName
     * @param type $path
     * @param type $thumbnail
     * @return type
     */
    public function filterFileUpload(Request $request, array $oldArray, $fieldName, $path, $thumbnail = null)
    {
        $thumbNail = null;
        $photos = $request->all();
        $photsArr = ksort($photos[$fieldName]);
        foreach ($photos[$fieldName] as $key => $file) {
            $originalName = $file->getClientOriginalName(); //get upload file list
            if (! in_array($originalName, $oldArray)) {
                //============Upload thumbnail when user change the first image=====================
                if ($key > 0) :
                    $thumbnail = null;
                endif;
//              ======upload file============
                $moveUploadFile = $this->updateFiles($file, $fieldName, $path, $thumbnail);
                if (! is_array($moveUploadFile)) {
                    return $moveUploadFile;
                }
                $newList[$key] = $moveUploadFile[$fieldName][0];

                //==========Check update property thumbnail===============
                if ($thumbnail != null) :
                    $thumbNail = $moveUploadFile['thumbnail'];
                endif;

//                ========unlink file============
                if (isset($oldArray[$key])) {
                    $this->removeFile($path.$oldArray[$key]);
                }
            } else {
                $newList[$key] = $originalName;
            }
        }

        return [$fieldName => $newList, 'thumbnail' => ($thumbNail != null) ? $thumbNail : null];
    }

    /**
     * @param type $urls
     * @param type $oldArray
     * @param type $fieldName
     * @param type $path
     * @param type $thumbnail
     * @return type array name
     */
    public function filterFileUploadWithUrl($urls, array $oldArray, $fieldName, $path, $thumbnail = null)
    {
        $thumbNail = null;
        foreach ($urls as $key => $url) {
            $originalName = basename($url); //get upload file list
            if (! in_array($originalName, $oldArray)) {
                //============Upload thumbnail when user change the first image=====================
                if ($key > 0) :
                    $thumbnail = null;
                endif;
//              ======upload file============
                $moveUploadFile = $this->updateFileByUrl($url, $fieldName, $path, $thumbnail);
                if (! is_array($moveUploadFile)) :
                    return $moveUploadFile;
                endif;

                //==========Check update property thumbnail===============
                if ($thumbnail != null) :
                    $thumbNail = $moveUploadFile['thumbnail'];
                endif;

                $newList[$key] = $moveUploadFile[$fieldName][0];
//                ========unlink file============
                if (isset($oldArray[$key])) {
                    $this->removeFile($path.$oldArray[$key]);
                }
            } else {
                $newList[$key] = $originalName;
            }
        }
//        return $newList;
        return [$fieldName => $newList, 'thumbnail' => ($thumbNail != null) ? $thumbNail : null];
    }

    /**
     * @param Request $request
     * @return Instance of Intervention\Image\Image;
     */
    public function uploadWatermark(Request $request)
    {
        $image = $request->file('photos');
        $slug = 'bgh-dsd';
        $key = 0;
        $fileName = 'img-'.$slug.'-'.$key.'.'.strtolower($image->getClientOriginalExtension());
        $destinationPath = self::$property_photos_path;

        //Upload Images One After the Order into folder
        $img = Image::make($image->getRealPath());
        $watermarkLogo = Image::make('uploads/logo_watermark.png');
        $watermarkUrl = Image::make('uploads/url_watermark.png');

        // resize the image to a height of 200 and constrain aspect ratio (auto width)
        $img->resize(639, 500, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->insert($watermarkLogo, 'top-left', 10, 10);
        $img->insert($watermarkUrl, 'bottom-center', 10, 10);
        $result = $img->save($destinationPath.'/'.$fileName, 100);
        if (! $result) {
            return $this->getResponseData('0', 'File update load faild.', '');
        }

        return $this->getResponseData('1', 'File upload successfull.', '');
    }

    /**
     * Function to check multi file upload.
     * @param  array $staticRules list input to validate
     * @param  array $data requested data
     * @param  string $strFileName input name that concent the file
     * @return array List of file name from generate number and time
     */
    public function removeAndUploadFile($file, $path = null)
    {
        try {
            if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                return ['status' => false, 'respond' => \Exception($file->getClientSize())];
            }
            $fileName = $this->generateFileName($file);

            $file->move($path, $fileName);

            return ['status' => true, 'respond' => $fileName];
        } catch (\Exception $e) {
            return ['status' => false, 'respond' => $e->getMessage()];
        }
    }

    public function softDelete($id)
    {
        $responder = new ResponderController;
        $property = Properties::find($id);
        if (! $property) {
            return $responder->returnMessage(0, 'Property', 5);
        }
        $property->delete();

        return $responder->returnMessage(1, 'Property', 2, $property);
    }

    /**
     * Generate message back to the request object.
     * @param  string  $ObjectName
     * @return Status of request and massage from the request
     * @Example: 'Property successfully update', 'Property not update'
     */
    public function returnMessage($ObjectName, $messageTypeId)
    {
        $messageType = ['status', 'danger'];
        $ms = [' Successfully Updated..!!', ' Not Updated..!!'];

        return $str = [$messageType[$messageTypeId], $ObjectName.' '.$ms[$messageTypeId]];
    }

    /**
     * Check result true or fails request and return the message.
     * @param  array  $Object​​ query request result
     * @param  string $objectName name of object to print out the message
     * @return string massage about query  and massage from the request
     */
    public function returnResult($object, $objectName)
    {
        if ($object) {
            $sms = $this->returnMessage($objectName, 0);
        } else {
            $sms = $this->returnMessage($objectName, 1);
        }

        return response()->json($sms, 201);
    }

    /**
     * Video File Upload.
     * @param  array user input data
     * @return ​Instance of Intervention\Image\Image;
     * List of photos have been uploaded
     */
    public function videoUpload(Request $request, $path = null)
    {
        $file = $request->file('videos');
        try {
            if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                return false;
            }
            $fileName = $this->generateFileName($file);
            $file->move($path, $fileName);

            return $fileName;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Multiple File Upload.
     * @param  array user input data
     * @return ​Instance of Intervention\Image\Image;
     * List of photos have been uploaded
     */
    public function doFileUpload($request = null, $inputName = null, $path = null, $thumbnail = null)
    {
        $files = $request->file($inputName);
        if ($path == null) {
            $path = self::$property_photos_path;
        }
        try {
            $resize = [
                'width' => null,
                'height' => 768,
            ];
            if ($thumbnail != null) :
                $arrayName['thumbnail'] = '';
            $arrayName[$inputName] = [];
            foreach ($files as $key => $file) :
                    if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                        throw new \Exception($file->getClientSize());
                    }
            $fileName = $this->generateFileName($file);
            //======================Upload resize image=============================
            $this->uploadResizeImage($file, $fileName, $path, $resize);

            //===========Create thumbnail from the first image upload================================
            if ($key == 0) :
                        $this->uploadThumbnail($file, $fileName, $path);
            $arrayName['thumbnail'] = 'thumbnail-'.$fileName;

            endif;
            //==========================================================
            array_push($arrayName[$inputName], $fileName);
            endforeach;

            return $arrayName;
            endif;

            $arrayName = [];
            foreach ($files as $key => $file) :
                if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                    throw new \Exception($file->getClientSize());
                }

            $fileName = $this->generateFileName($file);
            //======================Upload resize image=============================
            $this->uploadResizeImage($file, $fileName, $path, $resize);

            array_push($arrayName, $fileName);
            endforeach;

            return $arrayName;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /*
     * Upload list image URL
     */

    public function urlFilesUpload($urls = null, $inputName = null, $path = null, $thumbnail = null)
    {
        if ($path == null) {
            $path = self::$property_photos_path;
        }
        try {
            $resize = [
                'width' => null,
                'height' => 768,
            ];
            $arrayName[$inputName] = [];
            foreach ($urls as $key => $url) :

                $fileContentInfo = get_headers($url, true);

            if (! isset($fileContentInfo['Content-Length'])) :
                    return $this->getResponseData('0', 'File upload faild. Find not found :'.$url, '');
            endif;
            $fileSize = $fileContentInfo['Content-Length'];

            if ($fileSize > UploadedFile::getMaxFilesize()) {
//                    throw new \Exception($fileSize);
                return $this->getResponseData('0', 'File upload size is not allow.:'.$url, '');
            }

            $file = pathinfo($url);
            $fileName = base64_encode(microtime()).'_findod_property.'.$file['extension'];
            //======================Upload resize image=============================
            $this->uploadResizeImageByURL($url, $fileName, $path, $resize);

            //===========Create thumbnail from the first image upload================================
            if ($key == 0) :
                    $arrayName['thumbnail'] = '';
            if ($thumbnail != null) :
                        $this->uploadThumbnail(null, $fileName, $path, $url);
            $arrayName['thumbnail'] = 'thumbnail-'.$fileName;
            endif;

            endif;
            //==========================================================
            array_push($arrayName[$inputName], $fileName);
            endforeach;

            return $arrayName;

            //=========================================================================
        } catch (\Exception $e) {
            return $this->getResponseData('0', 'File upload faild. Find not found :'.$url, '');
//            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param type $url
     * @param type $inputName
     * @param type $path
     * @param type $thumbnail
     * @return string
     */
    public function updateFiles($file, $inputName = null, $path = null, $thumbnail = null)
    {
        if ($path == null) {
            $path = self::$property_photos_path;
        }
        try {
            $resize = [
                'width' => null,
                'height' => 768,
            ];
            $arrayName[$inputName] = [];

            $fileSize = $file->getClientSize();
            ///=================Check image size befor update===========================
            if ($fileSize > UploadedFile::getMaxFilesize()) {
                return $this->getResponseData('0', 'File upload size is not allow:'.$fileSize, '');
            }
            $fileExtension = ($file->guessExtension()) ? $file->guessExtension() : 'jpeg';

            $fileName = base64_encode(microtime()).'_findod_property.'.$fileExtension;
            //======================Upload resize image=============================
            $this->uploadResizeImage($file, $fileName, $path, $resize);

            //===========Create thumbnail from the first image upload================================
            if ($thumbnail != null) :
                $arrayName['thumbnail'] = '';
            $this->uploadThumbnail(null, $fileName, $path, $file);
            $arrayName['thumbnail'] = 'thumbnail-'.$fileName;
            endif;
            //==========================================================
            array_push($arrayName[$inputName], $fileName);

            return $arrayName;
            //=========================================================================
        } catch (\Exception $e) {
            return $this->getResponseData('0', 'File upload faild. Find not found :'.$file, '');
        }
    }

    /*
     * Upload list image URL
     */

    public function updateFileByUrl($url = null, $inputName = null, $path = null, $thumbnail = null)
    {
        if ($path == null) {
            $path = self::$property_photos_path;
        }
        try {
            $resize = [
                'width' => null,
                'height' => 768,
            ];
            $arrayName[$inputName] = [];

            $fileContentInfo = get_headers($url, true);
            //==============Check reach URL of image====================================
            if (! isset($fileContentInfo['Content-Length'])) :
                return $this->getResponseData('0', 'File upload faild. Find not found :'.$url, '');
            endif;
            $fileSize = $fileContentInfo['Content-Length'];
            ///=================Check image size befor update===========================
            if ($fileSize > UploadedFile::getMaxFilesize()) {
                return $this->getResponseData('0', 'File upload size is not allow.:'.$url, '');
            }

            $file = pathinfo($url);
            $fileName = base64_encode(microtime()).'_findod_property.'.$file['extension'];
            //======================Upload resize image=============================
            $this->uploadResizeImageByURL($url, $fileName, $path, $resize);

            //===========Create thumbnail from the first image upload================================
            if ($thumbnail != null) :
                $arrayName['thumbnail'] = '';
            $this->uploadThumbnail(null, $fileName, $path, $url);
            $arrayName['thumbnail'] = 'thumbnail-'.$fileName;
            endif;
            //==========================================================
            array_push($arrayName[$inputName], $fileName);

            return $arrayName;
            //=========================================================================
        } catch (\Exception $e) {
            return $this->getResponseData('0', 'File upload faild. Find not found :'.$url, '');
        }
    }

    /**
     * @param type $file
     * @param type $fileName
     * @param type $path
     * @param type $resize
     */
    private function uploadResizeImage($file, $fileName, $path, $resize = null)
    {
        if ($resize == null) :
            $resize = [
                'width' => null,
                'height' => 768,
            ];
        endif;
//        if ($resize != null):
        $imgResize = Image::make($file->getRealPath());
        $getResize = $this->checkSize($resize);
//        $resizeFileName = $getResize['str'] . '-' . $fileName;
        // resize the image to a height of 500 and constrain aspect ratio (auto width)
        $imgResize->resize($getResize['width'], $getResize['height'], function ($constraint) {
            $constraint->aspectRatio();
        });

        $watermarkLogo = Image::make('uploads/logo_watermark.png');
        $watermarkUrl = Image::make('uploads/url_watermark.png');

        $imgResize->insert($watermarkLogo, 'top-left', 10, 10);
        $imgResize->insert($watermarkUrl, 'bottom-center', 10, 10);
        $imgResize->save($path.'/'.$fileName);
    }

    private function uploadResizeImageByURL($imageURL, $fileName, $path)
    {
        $resize = [
            'width' => null,
            'height' => 768,
        ];
//        if ($resize != null):
        $imgResize = Image::make($imageURL);
        $getResize = $this->checkSize($resize);
        $resizeFileName = $getResize['str'].'-'.$fileName;
        // resize the image to a height of 500 and constrain aspect ratio (auto width)
        $imgResize->resize($getResize['width'], $getResize['height'], function ($constraint) {
            $constraint->aspectRatio();
        });

        $watermarkLogo = Image::make('uploads/logo_watermark.png');
        $watermarkUrl = Image::make('uploads/url_watermark.png');

        $imgResize->insert($watermarkLogo, 'top-left', 10, 10);
        $imgResize->insert($watermarkUrl, 'bottom-center', 10, 10);
        $imgResize->save($path.'/'.$fileName);
//        endif;
//=========================raw file size upload================
//        $img = Image::make($imageURL);
//        $watermarkLogo = Image::make('uploads/logo_watermark.png');
//        $watermarkUrl = Image::make('uploads/url_watermark.png');
//
//        $img->insert($watermarkLogo, 'top-left', 10, 10);
//        $img->insert($watermarkUrl, 'bottom-center', 10, 10);
//        $img->save($path . '/' . $fileName);
    }

    /*
     * return array width &  height
     *        string  $str string of width X height
     *
     */

    private function checkSize($size)
    {
        $width = $height = null;
        $str = '';
        if (isset($size['width']) && $size['width'] > 0) :
            $width = $size['width'];
        $str .= $width.'x';
        endif;
        if (isset($size['height']) && $size['height'] > 0) :
            $height = $size['height'];
        $str .= $height;
        endif;
        $data = ['str' => $str, 'width' => $width, 'height' => $height];

        return $data;
    }

    private function uploadThumbnail($file, $fileName, $path, $url = null)
    {
        if ($url != null) :
            $img = Image::make($url); else :
            $img = Image::make($file->getRealPath());
        endif;

        // require resize the image to a height =426 and width= 639 constrain aspect ratio

        $img->resize(null, 426, function ($constraint) {
            $constraint->aspectRatio();
        });
        $thumbnailPath = $path.'/thumbnails';
        $img->save($thumbnailPath.'/'.'thumbnail-'.$fileName);
    }

    public function backendUploadAmenity(Request $request)
    {
        $responder = new ResponderController;
        $apiToken = $request->header('api-token');
        if (! $apiToken) {
            return $responder->returnMessage(0, null, null, 'API Token is request.');
        }
        $user = \App\Users::where('api_token', $apiToken)
                        ->where('userol_id', self::$admin_role)->first();
        if (! $user) {
            return $responder->returnMessage(0, null, null, 'Unauthorized Access. please check your API token with administrator.');
        }

        $validator = Validator::make($request->all(), [
//                    'amenity' => 'required|image|dimensions:max_width=100,max_height=100|mimes:png,jpg,gif,svg,jpeg'
                    'amenity' => 'required|image|mimes:png,jpg,gif,svg,jpeg',
                        ]
        );
        if ($validator->fails()) :
            return $responder->returnMessage(0, 6, 6, $validator->errors()->first());
        endif;
        $doUploadFile = $this->amenityFileUpload($request->file('amenity'), self::$amenity_directory);
        if (! $doUploadFile) {
            return $responder->returnMessage(0, 6, 7, $doUploadFile);
        }

        return $responder->returnMessage(1, null, null, $doUploadFile);
    }

    /**
     * @param Request $request
     * @return type
     */
    public function backendUploadResidence(Request $request)
    {
        $responder = new ResponderController;
        $apiToken = $request->header('api-token');
        if (! $apiToken) {
            return $responder->returnMessage(0, null, null, 'API Token is request.');
        }
        $user = \App\Users::where('api_token', $apiToken)
                        ->where('userol_id', self::$admin_role)->first();
        if (! $user) {
            return $responder->returnMessage(0, null, null, 'Unauthorized Access. please check your API token with administrator.');
        }

        $validator = Validator::make($request->all(), [
//                    'residence' => 'required|image|dimensions:max_width=100,max_height=100|mimes:png,jpg,gif,svg,jpeg'
                    'residence' => 'required|image|mimes:png,jpg,gif,svg,jpeg',
                        ]
        );
        if ($validator->fails()) :
            return $responder->returnMessage(0, null, null, '', $validator->errors()->first());
        endif;

        $doUploadFile = $this->amenityFileUpload($request->file('residence'), self::$residence_directory);
        if (! $doUploadFile) {
            return $responder->returnMessage(0, 6, 7, $doUploadFile);
        }

        return $responder->returnMessage(1, null, null, $doUploadFile);
    }

    /**
     * @param Request $request
     * @return type
     */
    public function backendUploadAdvertisement(Request $request)
    {
        $responder = new ResponderController;
        $apiToken = $request->header('api-token');
        if (! $apiToken) {
            return $responder->returnMessage(0, null, null, 'API Token is request.');
        }
        $user = \App\Users::where('api_token', $apiToken)
                        ->where('userol_id', self::$admin_role)->first();
        if (! $user) {
            return $responder->returnMessage(0, null, null, 'Unauthorized Access. please check your API token with administrator.');
        }

        $validator = Validator::make($request->all(), [
                    'advertisement' => 'required|image|dimensions:min_width=100,min_height=100|mimes:png,jpg,gif,svg,jpeg', ]
        );
        if ($validator->fails()) :
            return $responder->returnMessage(0, null, null, '', $validator->errors()->first());
        endif;

        $doUploadFile = $this->advertisementFileUpload($request->file('advertisement'), self::$advertisement_directory);
        if (! $doUploadFile) {
            return $responder->returnMessage(0, 6, 7, $doUploadFile);
        }

        return $responder->returnMessage(1, null, null, $doUploadFile);
    }

    public function backendUploadPhotos(Request $request)
    {
        $responder = new ResponderController;

        $apiToken = $request->header('api-token');
        if (! $apiToken) {
            return $responder->returnMessage(0, null, null, 'API Token is request.');
        }
        $user = \App\Users::where('api_token', $apiToken)
                ->first();
        if (! $user) {
            return $responder->returnMessage(0, null, null, 'Unauthorized Access. please check your API token with administrator.');
        }

        $validator = $this->validatePhotos($request, 'photos');
        if ($validator->fails()) :
            return $responder->returnMessage(0, 'Property', 6, $validator->errors()->first());
        endif;
        $fileUploadLimited = $this->limitedFileUpload($request->file('photos'), 3, 15);
        if (! $fileUploadLimited['status']) {
            return $fileUploadLimited['message'];
        }
        $doUploadFile = $this->doFileUpload($request, 'photos', self::$property_photos_path, true);
        if (! $doUploadFile) {
            return $responder->returnMessage(0, 'Property', 6, $doUploadFile);
        }

        return $responder->returnMessage(1, 'Property', 2, $doUploadFile);
    }

    public function backendUploadPlans(Request $request)
    {
        $responder = new ResponderController;
        $apiToken = $request->header('api-token');
        if (! $apiToken) {
            return $responder->returnMessage(0, null, null, 'API Token is request.');
        }
        $user = \App\Users::where('api_token', $apiToken)
                ->first();
        if (! $user) {
            return $responder->returnMessage(0, null, null, 'Unauthorized Access. please check your API token with administrator.');
        }

        $validator = $this->validatePhotos($request, 'plans');
        if ($validator->fails()) :
            return $responder->returnMessage(0, 'Property', 6, $validator->errors()->first());
        endif;

        $fileUploadLimited = $this->limitedFileUpload($request->file('plans'), 0, 5);
        if (! $fileUploadLimited['status']) {
            return $fileUploadLimited['message'];
        }

        $doUploadFile = $this->doFileUpload($request, 'plans', self::$property_plan_path);
        if (! $doUploadFile) {
            return $responder->returnMessage(0, 'Property', 6, $doUploadFile);
        }

        return $responder->returnMessage(1, 'Property', 2, $doUploadFile);
    }

    /**
     * @param type $files
     * @return array  ['status','message']
     */
    public function limitedFileUpload($files, $min = null, $max = null)
    {
        $responder = new ResponderController;
        $counter = count($files);
        if (($counter >= $min && $counter <= $max)) {
            return ['status' => true];
        }

        return ['status' => false, 'message' => $responder->returnMessage(0, null, null, '', trans('messages.fileLenght'))];

//        $this->getResponseData("0", "Data validation failed.", "File lenght validation failed.")];
    }

    /**
     * Generate file name  as random with date time.
     * @param  string $extension file extension
     * @return ​string new file name with time and random number from 5-10000
     */
    public static function generateFileName($file)
    {
        //building the file name
        $fileName = base64_encode(microtime()).'_findod_property';
        $fullFileName = '';
        if (! is_null($file->guessExtension())) {
            $fullFileName = $fileName.'.'.$file->guessExtension();
        } else {
            $fullFileName = $fileName.'.jpeg';
        }

        return $fullFileName;
    }

    /**
     * Get list of amenities from a give residence id.
     * @param  int $id  id of the residence given when click a list of residence
     * @return ​array List of amenities
     */
    public function getAmenities($id)
    {
        $responder = new ResponderController;
        $getResidence = Residence::find($id);
        if ($getResidence != null) :
            $amenitiesList = $getResidence['res_amenities'];
        $explode_id = array_map('intval', explode(',', $amenitiesList));
        $amenities = \App\Amenities::whereIn('id', $explode_id)
                    ->get();

        return $responder->returnMessage(1, null, null, $amenities); else :
            return $responder->returnMessage(0, 'Residence', 5);
        endif;
    }

    /**
     * Get list of residences.
     * @param
     * @return ​array List of residence
     */
    public function getResidence()
    {
        $responder = new ResponderController;
        $getResidence = Residence::where('status', 1)->orderBy('position')->get()->toArray();
        if ($getResidence != null) :
            return $responder->returnMessage(1, null, null, $getResidence); else :
            return $responder->returnMessage(0, 'Residence', 5);
        endif;
    }

    /**
     * Get list of residences.
     * @param
     * @return ​array List of residence
     */
    public function getResidenceByType()
    {
        $responder = new ResponderController;
        $getResidence = \App\ResidenceType::with('residence')->get()->toArray();
        if ($getResidence != null) :
            return $responder->returnMessage(1, null, null, $getResidence); else :
            return $responder->returnMessage(0, 'Residence', 5);
        endif;
    }

    /**
     * Set trending information when click view a property.
     * @param  $propertyId property to recode the trending
     * @return ​
     */
    private function setTrending($propertyId = null)
    {
        $trending = new Trendings();
        $currentDate = \Illuminate\Support\Carbon::now()->format('Y-m-d');
        $udateTrending = $trending->where('tre_pro_id', $propertyId)
                ->where('tre_date', $currentDate)
                ->update(['tre_counter' => DB::raw('tre_counter+1')]);

        if (! $udateTrending) :
            $trending->tre_date = $currentDate;
        $trending->tre_pro_id = $propertyId;
        $trending->tre_counter = 1;
        $trending->save();
        endif;

        //$results = DB::select("call proUpdateTrending($propertyId,CURDATE())"); // call to procedure database with two arguments
//        return $trending;
    }

    /**
     * Find property march with dynamic given field.
     * @param  array  $request list of key and value proposal
     * @return array List of properties
     */
    public function filter(Request $request, $sort = null)
    {
        $properties = new Properties();
        $responder = new ResponderController;
//        $returnResult = array();
//        ================ Example ==================
        //// Search for a property column dynamic
        $propertyColumn = ['residence', 'search_type', 'parking', 'status'];
        //'bed_rooms', 'bath_rooms','floor'
        $query = $properties->newQuery();
        $selectColumn = [
            'properties.id',
            'pro_currency',
            'pro_title',
            'pro_rating',
            'pro_status',
            'pro_parking',
            'pro_floor',
            'pro_address',
            'pro_price',
            'pro_lat',
            'pro_lng',
            'pro_residence',
            'pro_use_id',
            'pro_search_type',
            'pro_bed_rooms',
            'pro_bath_rooms',
            'pro_age',
            'pro_square_feet',
            'favorites_count',
            'properties.created_at',
            'pro_thumbnail',
            'project_name_id',
        ];
        $query->select('id');

        $validator = Validator::make($request->all(), ['current_lat' => 'required', 'current_lng' => 'required']);
        if ($validator->fails()) : // check user input
            return $this->getResponseData('0', $validator->errors()->first(), '');
        endif;

        foreach ($propertyColumn as $key) : // Dynamic field search from user input

            if ($request->has($key) && $request->input($key) != '' && $request->input($key) > 0) :
                $query->where('pro_'.$key, $request->input($key));
        endif;
        endforeach;

        $FilterColumn = ['bed_rooms', 'bath_rooms', 'floor'];

        foreach ($FilterColumn as $key) :

            if ($request->has($key) && $request->input($key) != '' && $request->input($key) > 0) :
                if ($request->input($key) == '10+') :
                $query->whereBetween('pro_'.$key, ['10', '99']); else :
                $query->where('pro_'.$key, $request->input($key));
        endif;
        endif;
        endforeach;

        if ($request->has('age') && $request->input('age') != '') {
            if ($request->input('age') < 5) {
                $query->where('pro_age', '<=', $request->input('age'));
            } else {
                $query->where('pro_age', '>', 4);
            }
        }

        //===================Search property by project name===================
        if ($request->has('project_name_id') && $request->input('project_name_id') != '') {
            $query->where('project_name_id', $request->input('project_name_id'));
        }

        // Search for a property min & max price.
        if ($request->has('min-price') && $request->has('max-price')) {
            $min = $request->input('min-price');
            $max = $request->input('max-price');
            if ($max > $min && $max > 0) :
                $query->whereBetween('pro_price', [$min, $max]);
            endif;
            if ($max == $min && $max > 0) :
                $query->where('pro_price', $max);
            endif;
        }

        // Search for a property min & max square feet.
        if ($request->has('min_square_feet') && $request->has('max_square_feet')) {
            $min = $request->input('min_square_feet');
            $max = $request->input('max_square_feet');
            if ($max > $min && $max > 0) {
                $query->whereBetween('pro_square_feet', [$min, $max]);
            }
        }

        // Search amenitest selected by user
        if ($request->has('amenities')) :
            $getPropertyByAmenit = $properties->getPropertyByAmenities($request);
        $query->whereIn('properties.id', $getPropertyByAmenit);

        endif;
        if ($request->has('user_role') && $request->input('user_role') != '' && $request->input('user_role') > 0) :
            $userList = \App\Users::where('userol_id', $request->input('user_role'))->get();
        $ids = [];
        foreach ($userList as $q) {
            array_push($ids, $q->id);
        }
        $query->whereIn('pro_use_id', $ids);

//            $query->join('users', 'users.id', 'pro_use_id');
//            $query->where('userol_id', $request->input('user_role'));
        endif;

        if ($request->has('lat') && $request->input('lat') != '' && $request->has('lng') && $request->input('lng') != '') {
            $ids = $this->filterLocation($request);
            $query->whereIn('properties.id', $ids);
        }

        // Search by polygon
        if ($request->has('polygon')) {
            $strPolygon = "'".implode(',', $request->polygon).','.$request->polygon[0]."'";

            $propertyList = Properties::getPropertyByPolygon($strPolygon, 'id');
            if (empty($propertyList)) {
                return $responder->returnMessage(0, 'Property', 1, []);
            }
            $ids = [];
            //Extract the id's
            foreach ($propertyList as $q) {
                array_push($ids, $q->id);
            }
            $query->whereIn('properties.id', $ids);
        }
        $query->with('currency', 'propertyType');

//        ==================Sort data==========================
        $params = $request->query();
        if (isset($params['sort'])) :

            $sortParams = [
                1 => ['created_at' => 'asc'],
                2 => ['created_at' => 'desc'],
                3 => ['pro_price' => 'asc'],
                4 => ['pro_price' => 'desc'],
                5 => ['pro_title' => 'asc'],
                6 => ['pro_title' => 'desc'],
            ];
        if (array_key_exists($params['sort'], $sortParams)) :
                $sortBy = $sortParams[$params['sort']];
        foreach ($sortBy as $key => $value) :
                    $query->orderBy('properties.'.$key, $value);
        endforeach;
        endif;
        endif;
//        ========================================================
//        $query->paginate(self::$number_per_page);
        $propertyList = [];
        try {
            $propertyList = $query->paginate(self::$number_per_page)->toArray();
            $lastUpdateId = [];
            foreach ($propertyList['data'] as $property) {
                array_push($lastUpdateId, $property['id']);
            }

            $lat = $request->input('current_lat');
            $lng = $request->input('current_lng');
            $distance = 10; //  10 km
            $arraySelectColumn = implode(',', $selectColumn);
            if ($request->has('polygon')) {
                $propertyListWithDistance = Properties::getPropertyDistanceByCurrenctLocation($lat, $lng, $arraySelectColumn, $lastUpdateId)->toArray();
            } else {
                $propertyListWithDistance = Properties::getPropertyByCurrenctLocation($lat, $lng, $distance, $arraySelectColumn, $lastUpdateId)->toArray();
            }
            //=============Keep user search histor========================
            $this->createSearchHistory($request, $propertyListWithDistance);
            //===========================================================
            if (empty($propertyListWithDistance)) {
                return $responder->returnMessage(0, 'Property', 5, []);
            }
//            return $responder->returnMessage(1, NULL, 2, $this->generatePropertyList($propertyList));
            return $responder->returnMessage(1, null, 2, $propertyListWithDistance);
        } catch (\Illuminate\Database\QueryException $e) {
            //=============Keep user search histor========================
            $this->createSearchHistory($request, $propertyList);
            //===========================================================
            return $responder->returnMessage(0, 'Property', 1, $e);
        }
    }

    /**
     * Find property march with dynamic given field.
     * @param  array  $request list of key and value proposal
     * @return array List of properties
     */
    public function filterByWeb(Request $request, $sort = null)
    {
        $properties = new Properties();
        $responder = new ResponderController;
//        ================ Example ==================
        //// Search for a property column dynamic
        $propertyColumn = ['residence', 'bed_rooms', 'bath_rooms', 'search_type', 'parking', 'status', 'floor'];

        $query = $properties->newQuery();
        $selectColumn = [
            'properties.id',
            'pro_currency',
            'pro_title',
            'pro_rating',
            'pro_status',
            'pro_parking',
            'pro_floor',
            'pro_address',
            'pro_price',
            'pro_lat',
            'pro_lng',
            'pro_residence',
            'pro_use_id',
            'pro_search_type',
            'pro_bed_rooms',
            'pro_bath_rooms',
            'pro_age',
            'pro_square_feet',
            'favorites_count',
            'properties.created_at',
            'pro_thumbnail',
        ];
        $query->select($selectColumn);

        foreach ($propertyColumn as $key) : // Dynamic field search from user input

            if ($request->has($key) && $request->input($key) != '' && $request->input($key) > 0) :
                $query->where('pro_'.$key, $request->input($key));
        endif;
        endforeach;

        if ($request->has('age') && $request->input('age') != '') {
            if ($request->input('age') < 5) {
                $query->where('pro_age', '<=', $request->input('age'));
            } else {
                $query->where('pro_age', '>', 4);
            }
        }

        //===================Search property by project name===================
        if ($request->has('project_name_id') && $request->input('project_name_id') != '') {
            $query->where('project_name_id', $request->input('project_name_id'));
        }

        // Search for a property min & max price.
        if ($request->has('min-price') && $request->has('max-price')) {
            $min = $request->input('min-price');
            $max = $request->input('max-price');
            if ($max > $min && $max > 0) :
                $query->whereBetween('pro_price', [$min, $max]);
            endif;
            if ($max == $min && $max > 0) :
                $query->where('pro_price', $max);
            endif;
        }

        // Search for a property min & max square feet.
        if ($request->has('min_square_feet') && $request->has('max_square_feet')) {
            $min = $request->input('min_square_feet');
            $max = $request->input('max_square_feet');
            if ($max > $min && $max > 0) {
                $query->whereBetween('pro_square_feet', [$min, $max]);
            }
        }

        // Search amenitest selected by user
        if ($request->has('amenities')) :
            $getPropertyByAmenit = $properties->getPropertyByAmenities($request);
        $query->whereIn('properties.id', $getPropertyByAmenit);

        endif;
        if ($request->has('user_role') && $request->input('user_role') != '' && $request->input('user_role') > 0) :
            $query->join('users', 'users.id', 'pro_use_id');
        $query->where('userol_id', $request->input('user_role'));
        endif;

        if ($request->has('lat') && $request->input('lat') != '' && $request->has('lng') && $request->input('lng') != '') {
            $ids = $this->filterLocation($request);
            $query->whereIn('properties.id', $ids);
        }

        // Search by polygon
        if ($request->has('polygon')) {
            $strPolygon = "'".implode(',', $request->polygon).','.$request->polygon[0]."'";

            $propertyList = Properties::getPropertyByPolygon($strPolygon, 'id');
            if (empty($propertyList)) {
                return $responder->returnMessage(0, 'Property', 1, []);
            }
            $ids = [];
            //Extract the id's
            foreach ($propertyList as $q) {
                array_push($ids, $q->id);
            }
            $query->whereIn('properties.id', $ids);
        }
        $query->with('currency', 'propertyType');

//        ==================Sort data==========================
        $params = $request->query();

        if (isset($params['sort'])) :
            $sortParams = [
                'date_asc' => ['created_at' => 'asc'],
                'date_desc' => ['created_at' => 'desc'],
                'price_asc' => ['pro_price' => 'asc'],
                'price_desc' => ['pro_price' => 'desc'],
                'title_asc' => ['pro_title' => 'asc'],
                'title_desc' => ['pro_title' => 'desc'],
            ];
        if (array_key_exists($params['sort'], $sortParams)) :
                $sortBy = $sortParams[$params['sort']];
        foreach ($sortBy as $key => $value) :
                    $query->orderBy('properties.'.$key, $value);
        endforeach;
        endif;
        endif;

//        ========================================================
//        $query->paginate(self::$number_per_page);
        $allProperty = [];
        if (! isset($params['page'])) :
            $allProperty = $query->get();
        endif;

        $propertyList = [];

        try {
            $propertyList = $query->get()->toArray();
            $lastUpdateId = [];
            foreach ($propertyList as $property) {
                array_push($lastUpdateId, $property['id']);
            }
            $lat = $request->input('current_lat');
            $lng = $request->input('current_lng');

            if (array_key_exists($params['sort'], $sortParams)) :
                $sortBy = $sortParams[$params['sort']];
            $sort = [];
            $data = [];
            foreach ($sortBy as $key => $value) :
                    $sort = $key;
            $data = $value;
            endforeach;
            endif;

            $arraySelectColumn = implode(',', $selectColumn);
            $propertyListWithDistance = Properties::getPropertyDistanceByCurrenctLocationByWeb($lat, $lng, $arraySelectColumn, $lastUpdateId, $sort, $data)->toArray();

            //=============Keep user search histor========================
//            $this->createSearchHistory($request, $propertyListWithDistance);
            //===========================================================
            if (empty($propertyListWithDistance)) {
                return $responder->returnMessage(0, 'Property', 5, []);
            }

            return $responder->returnMessage(1, null, 2, ['property_list' => $propertyListWithDistance, 'property_by_map' => $allProperty]);
        } catch (\Illuminate\Database\QueryException $e) {
            //=============Keep user search histor========================
            $this->createSearchHistory($request, $propertyList);
            //===========================================================
            return $responder->returnMessage(0, 'Property', 1, $propertyList);
        }
    }

    public function filterLocation($request)
    {
        $distance = 10; //Set defauld 10 km
        if ($request->has('distance')) {
            $distance = $request->input('distance');
        }
        $query = Properties::getByDistance($request->lat, $request->lng, $distance);
        $ids = [];
        if (empty($query)) {
            return $ids;
        }
        //Extract the id's
        foreach ($query as $q) {
            array_push($ids, $q->id);
        }

        return $ids;
    }

    /**
     * Store all request from user when search property.
     * @param  array  $request list of key and value proposal
     * @return
     */
    private function createSearchHistory(Request $request, $propertyList)
    {
        $searchHistory = new \App\SearchHistory();
        $user = Auth::user();
        $searchHistory->user_id = (($user != null) ? $user->id : null);
        $searchHistory->request_query = json_encode($request->except(['page', 'sort']));
        $searchHistory->request_result = json_encode($propertyList);
        $searchHistory->save();
    }

    private function amenityFileUpload($file, $path)
    {
        try {
            if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                throw new \Exception($file->getClientSize());
            }
            //building the file name
            $fullFileName = $this->generateFileName($file);
            //upload file the path
            //please specify the protected $user_avatar_directory in this Controller in the top
            $file->move($path, $fullFileName);

            return $fullFileName;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function advertisementFileUpload($file, $path)
    {
        try {
            if ($file->getClientSize() > UploadedFile::getMaxFilesize()) {
                throw new \Exception($file->getClientSize());
            }
            //building the file name
            $fullFileName = $this->generateFileName($file);
            //upload file the path
            $img = Image::make($file);
            // resize the image to a height of 430 and constrain aspect ratio (auto width)
//            $img->resize(null, 470, function ($constraint){
//                $constraint->aspectRatio();
//            });
            $img->save($path.'/'.$fullFileName, 100);
            $this->uploadThumbnail($file, $fullFileName, $path);

            return $fullFileName;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function insertFakProperties(Request $request)
    {
//        use Faker\Factory as Faker;
        $faker = \Faker\Factory::create();
        $dir = self::$property_photos_path;
        foreach (range(1, $request->input('record_number')) as $index) {
            $property = new Properties();
            $property->create([
                'pro_title' => 'Home for sale or rent',
                'pro_price' => $faker->numberBetween($min = 50, $max = 9000),
                'pro_address' => $faker->address(),
                'pro_lng' => $faker->longitude($min = -180, $max = 180),
                'pro_lat' => $faker->latitude($min = -90, $max = 90),
                'pro_residence' => $faker->numberBetween($min = 1, $max = 14),
                'pro_search_type' => $faker->numberBetween($min = 1, $max = 3),
                'pro_floor' => $faker->numberBetween($min = 1, $max = 14),
                'pro_bed_rooms' => $faker->numberBetween($min = 1, $max = 5),
                'pro_bath_rooms' => $faker->numberBetween($min = 1, $max = 5),
                'pro_city' => $faker->numberBetween($min = 1, $max = 5),
                'pro_photos' => json_encode([
                    $faker->image($dir, $width = 640, $height = 480, 'city', false),
                    $faker->image($dir, $width = 640, $height = 480, 'city', false),
                    $faker->image($dir, $width = 640, $height = 480, 'city', false),
                ]),
            ]);
        }
    }

    public function urlUpload(Request $request)
    {
        $path = self::$test_photos_path;
        $imageUrl = $request->input('url');
        $fileName = basename($imageUrl);
        $img = Image::make($imageUrl);
        $result = $img->save($path.'/'.$fileName);

//      $result = $this->doFileUpload($request = null, $inputName = null, $path = null, $thumbnail = null);
        dd('Success');
    }

//    public function urlUpload(Request $request) {
////        $path = self::$test_photos_path;
//        $imageUrl = $request->input('url');
//        dd($this->urlFilesUpload($imageUrl, null, null, TRUE));
//
////        $fileName = basename($imageUrl);
////        $img = Image::make($imageUrl);
////        $result = $img->save($path . '/' . $fileName);
////      $result = $this->doFileUpload($request = null, $inputName = null, $path = null, $thumbnail = null);
//        dd("Success");
//    }
}
