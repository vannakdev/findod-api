<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use stdClass;

class SearchController extends Controller
{
    /**
     * Find property march with dynamic given field.
     * @param  array  $request list of key and value proposal
     * @return array List of properties
     */
    public function filter(Request $request, Properties $properties)
    {
        $responder = new ResponderController;
        $returnResult = [];
//        ================ Example ==================
        //// Search for a property feature
        $propertyFeature = ['residence', 'bed_rooms', 'bath_room', 'search_type', 'parking', 'age', 'status'];
        $query = $properties->newQuery();
        $selectColumn = ['properties.id', 'pro_title', 'pro_rating', 'pro_address', 'pro_price', 'currency.title as currency', 'pro_lat', 'pro_lng', 'pro_photos', 'pro_residence'];
        $query->select($selectColumn);
        foreach ($propertyFeature as $key):// Dynamic field search from user input
            if ($request->has($key)):
                $query->where('pro_'.$key, $request->input($key));
        endif;
        endforeach;
        // Search for a property min & max price.
        if ($request->has('price')) {
            $explode_id = array_map('intval', explode(',', $request->input('price')));
            $query->whereBetween('pro_price', [$explode_id[0], $explode_id[1]]);
        }
        if ($request->has('amenities')):
//            $getAmenities = $request->input('amenities');
            $query->with('amenities');
        endif;

        if ($request->has('user_role')):
            $query->join('users', 'pro_use_id', 'users.id');
        $query->join('user_role', 'users.userol_id', 'user_role.id');
        $query->where('user_role.id', $request->input('user_role'));
        endif;
        $query->join('currency', 'currency.id', 'pro_currency');
        $query->orderBy('properties.created_at', 'desc')
                ->take(25);
        try {
            $propertyList = $query->get();
            foreach ($propertyList as $key) {
                $pro = $key;
                $pro->pro_photos = json_decode($key->pro_photos);
                array_push($returnResult, $pro);
            }

            return $responder->returnMessage(1, null, 2, $returnResult);
        } catch (\Illuminate\Database\QueryException $e) {
            return $responder->returnMessage(0, 'Property', 1, '');
        }
    }

    public function filter001(Request $request, Properties $properties)
    {
        $responder = new ResponderController;
        $returnResult = [];
//        ================ Example ==================
        //// Search for a property feature
        $propertyFeature = ['residence', 'bed_rooms', 'bath_room', 'search_type', 'parking', 'age', 'status'];
        $query = $properties->newQuery();
        $selectColumn = ['properties.id', 'pro_title', 'pro_rating', 'pro_address', 'pro_price', 'currency.title as currency', 'pro_lat', 'pro_lng', 'pro_photos', 'pro_residence'];
        $query->select($selectColumn);
        foreach ($propertyFeature as $key):// Dynamic field search from user input
            if ($request->has($key)):
                $query->where('pro_'.$key, $request->input($key));
        endif;
        endforeach;
        // Search for a property min & max price.
        if ($request->has('price')) {
            $explode_id = array_map('intval', explode(',', $request->input('price')));
            $query->whereBetween('pro_price', [$explode_id[0], $explode_id[1]]);
        }
        if ($request->has('amenities')):
            $getAmenities = $request->input('amenities');
        $query->whereRaw("JSON_CONTAINS(pro_amenities,'$getAmenities')");
        endif;
        if ($request->has('user_role')):
            $query->join('users', 'pro_use_id', 'users.id');
        $query->join('user_role', 'users.userol_id', 'user_role.id');
        $query->where('user_role.id', $request->input('user_role'));
        endif;
        $query->join('currency', 'currency.id', 'pro_currency');
        $query->orderBy('properties.created_at', 'desc')
                ->take(25);
        try {
            $propertyList = $query->get();
            foreach ($propertyList as $key) {
                $pro = $key;
                $pro->pro_photos = json_decode($key->pro_photos);
                array_push($returnResult, $pro);
            }

            return $responder->returnMessage(1, null, 2, $returnResult);
        } catch (\Illuminate\Database\QueryException $e) {
            return $responder->returnMessage(0, 'Property', 1, '');
        }
    }
}
