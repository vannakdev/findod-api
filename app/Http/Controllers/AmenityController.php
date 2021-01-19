<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

//use Validator;
use App\Amenities;
use App\Residence;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\DB;

class AmenityController extends Controller
{
    public function __construct()
    {
    }

    public function showByResidence($id)
    {
//        $amenities = Amenities::find($id);

        $getResidence = Residence::find($id);
        $explode_id = array_map('intval', explode(',', $getResidence->res_amenities));

        $getAmenity = Amenities::whereIn('id', $explode_id)
//                ->with('AmenitiesTranslation')
                ->get();

        return $this->getResponseData('1', '', $getAmenity);
    }

    public function add(Request $request)
    {
        $amenities = new Amenities();
//        $amenities->eventInfo($request);
//        $amenities->icon = $request->input('icon');
//        $amenities->request = $request;

        $amenities->create($request->all());

        return $this->getResponseData('1', '', $amenities->translation()->first());
    }

    public function update($id, Request $request)
    {
        $amenities = Amenities::find($id);
        $amenities->request = $request;

        $amenities->update($request->all());

        return $this->getResponseData('1', '', $amenities->translation()->first());
    }
}
