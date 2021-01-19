<?php

/**
 * Global class for system notification.
 *
 * @author OU Sophea : ODIC
 */

namespace App\Http\Controllers;

use App\PropertyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Description of newPHPClass.
 *
 * @author OU Sophea : ODIC
 */
class PropertyReportController extends Controller
{
    public function __construct()
    {
    }

    protected static $number_per_page = 25;

    /**
     * Insert new property report recode with user information.
     * @param  Request  $request
     * @return
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $responder = new ResponderController;
        $validation = $responder->formValidater($request, [
            'property_id' => 'required|numeric|exists:properties,id',
            'comment' => 'max:200',
            'report_type_id' => 'required|numeric|exists:type_of_property_reports,id',
        ]);

        if ($validation != null): // check user input
            return $responder->returnMessage(0, null, null, '', $validation);
        endif;
        //create new report_property object and assign value from request's data
        $report = new PropertyReport();

        $result = $report->firstOrCreate([
            'property_id' => $request->input('property_id'),
            'user_id' => $user->id,
                ], ['comment' => $request->input('comment'), 'type_of_property_report_id' => $request->input('report_type_id')]);

        if (! $result->wasRecentlyCreated) {
            return $this->getResponseData('0', 'You are ready report about the property', '');
        }

        return $this->getResponseData('1', 'Report property have been created successfully', $result);
    }

    /**
     * A reporty view.
     */
    public function show($id)
    {
        $report = \App\PropertyReport::with('type_of_property_reports')
                        ->where('property_id', $id)->paginate(self::$number_per_page)->toArray();
        if (empty($report['data'])) {
            return $this->getResponseData('0', 'Report not found with given property ID', '');
        }

        return $this->getResponseData('1', '', $report['data']);
    }

    /**
     * List of reports.
     */
    public function showAll()
    {
        $report = \App\PropertyReport::with('type_of_property_reports')->get();

        return $report;
    }
}
