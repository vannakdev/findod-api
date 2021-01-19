<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\ProjectName;
use App\Properties;
use Illuminate\Http\Request;
use Validator;

/**
 * Description of newPHPClass.
 *
 * @author OU Sophea : ODIC
 */
class PropertyProjectNameController extends Controller
{
    public function __construct()
    {
    }

    protected static $number_per_page = 25;

    public function showAll()
    {
        $getProjectName = ProjectName::all();

        return $this->getResponseData('1', '', $getProjectName);
    }

    public function searchProjectName(Request $request)
    {
        $rules = [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'residence_id' => 'required|numeric|exists:residences,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->getResponseData('0', '', $validator->errors()->first());
        }
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $distance = 10; //  10 km
        $residence_id = $request->input('residence_id');

        $selectColumns = [
            'id',
            'address',
        ];
        $arraySelectColumn = implode(',', $selectColumns);

        $getPropertyByDistance = ProjectName::getProjectByDistance($lat, $lng, $distance, $residence_id, $arraySelectColumn);

        $nearByList = [];
        if (! empty($getPropertyByDistance)) {
            foreach ($getPropertyByDistance as $project):
                array_push($nearByList, $project->id);
            endforeach;
        }
        $projectList = ProjectName::whereIn('id', $nearByList)
                ->paginate(self::$number_per_page)->toArray();

        return $this->getResponseData('1', '', $projectList['data']);
    }
}
