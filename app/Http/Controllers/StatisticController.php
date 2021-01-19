<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\StatisticOfPriceRanges;
use Illuminate\Support\Facades\DB;

/**
 * Description of newPHPClass.
 *
 * @author OU Sophea : ODIC
 */
class StatisticController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Insert or update rating recode with user information.
     * @param  array  $data
     * @return user input validation or insert/ update property stars
     */
    public function createPriceRange($price)
    {
//        $priceRange = StatisticOfPriceRanges::max('max_price');
//       $next = 0;
        //============feature price with min and max range===========
        //====== 800 to max 1000    300 to max 400================
        $increasing = str_pad(1, strlen($price), '0', STR_PAD_RIGHT);
        $nextStep = str_pad(1, strlen($price) + 1, '0', STR_PAD_RIGHT);

        //======== $price from 100,200,300,400 the increasing is 100  ==============
        if (($price * 2) < $nextStep):
            $nextMax = $price + $increasing;
        $nextMin = $price;
        $increasing = $increasing; else:
            //======== $price from 500,600,700,800,900 the increasing is  1000 ==============
            $nextMax = $nextStep;
        $nextMin = $nextStep / 2;
        $increasing = $nextStep;
        endif;

        $priceRange = new StatisticOfPriceRanges();
//        ============  500-1000 , 1000-200 ===============
        $priceRange->max_price = $nextMax;
        $priceRange->min_price = $nextMin;
        $priceRange->increasing_number = $increasing;
        $priceRange->save();

        return $priceRange;
    }
}
