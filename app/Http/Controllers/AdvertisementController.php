<?php

/**
 * Global class for system notification
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Advertisement;
use Illuminate\Support\Carbon;

//use Illuminate\Support\Facades\DB;

class AdvertisementController extends Controller {

    public function __construct() {
        
    }

    static protected $number_per_page = 25;

    public function index() {

        $advertise = Advertisement::select('id', 'title', 'feature_image')
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
                ->latest()
                ->get(10);
        if(empty($advertise)){
            return [];
        }
        return $advertise;
    }

    public function showAll() {
        $advertises = Advertisement::with('tags')
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
                ->latest()
                ->paginate(self::$number_per_page)->toArray();

        return $this->getResponseData('1', "", $advertises['data']);
    }

    public function showAllWeb() {
        $advertises = Advertisement::with('tags')
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
                ->latest()
                ->paginate(self::$number_per_page);
        return $this->getResponseData('1', "", $advertises);
    }

    public function show($id) {
        $advertise = Advertisement::where('id', $id)->with('tags')->first();
        if (empty($advertise)):
            return $this->getResponseData('0', "Advertisement not found.", '');
        endif;
//        ============View count===========
        $advertise->view_count++;
        $advertise->save();
//        =================================


        return $this->getResponseData('1', "", $advertise);
    }

}
