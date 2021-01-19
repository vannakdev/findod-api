<?php

namespace App;

use App\TypeOfPropertyReport;
use Illuminate\Database\Eloquent\Model;

class PropertyReport extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'property_reports';
    protected $fillable = [
        'user_id',
        'property_id',
        'type_of_property_report_id',
        'comment',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo('App\Users', 'user_id');
    }

    public function property()
    {
        return $this->belongsTo('App\Properties', 'property_id');
    }

    public function type_of_property_reports()
    {
        return $this->belongsTo('App\TypeOfPropertyReport', 'type_of_property_report_id');
    }
}
