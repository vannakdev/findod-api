<?php

namespace App\Observers;

use App\Http\Controllers\NotificationController;
use App\Properties;
use Illuminate\Support\Carbon;

class PropertiesObserver
{
    public $module = 'Properties';

    /**
     * Listen to the Properties created event.
     *
     * @param  \App\Properties  $properties
     * @return void
     */
    public function created(Properties $property)
    {
//        ==================Sent notifiction to near by hosting property==================
//        $distance = 1;
//        $notify = new NotificationController();
//        $notify->sendNearbyUser($property-id, $property->pro_lat, $property->pro_lng, $distance);
//        return true;

        $publicId = $this->publicIdFormat($property->id);
        $property->pro_public_id = $publicId;
        $property->save();
    }

    public function updated(Properties $property)
    {
        if ($property->isDirty('pro_residence')):
            $property->amenities()->sync([]);
        endif;
    }

    private function publicIdFormat($id)
    {
        $t = Carbon::now();
        $newStr = substr($t->year, 2).str_pad($id, 4, '0', STR_PAD_LEFT);

        return 'ODR'.$newStr;
    }
}
