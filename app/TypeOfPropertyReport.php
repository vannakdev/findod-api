<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeOfPropertyReport extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'type_of_property_reports';
    protected $fillable = [
        'content'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'title',
        'content',
        'created_at',
        'updated_at'];
    
    protected $with=['translation'];

    public function translation() {
        return $this->hasMany('App\TypeOfPropertyReportTranslation', 'report_type_id')->where('locale', app('translator')->getLocale());
    }

}
