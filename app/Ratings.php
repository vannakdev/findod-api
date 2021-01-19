<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ratings extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'ratings';
    protected $fillable = [
        'user_id',
        'property_id',
        'stars',
        'comments',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function users() {
        return $this->belongsTo('App\Users', 'user_id');
    }

    public function properties() {
        return $this->belongsTo('App\Properties');
    }

    public function getPropertyRate($propertyId) {

        $results = DB::table('ratings')
                        ->select(DB::raw('
            property_id, 
    SUM(CASE WHEN (stars=1) THEN 1 ELSE 0 END) AS star1,
    SUM(CASE WHEN (stars=2) THEN 1 ELSE 0 END) AS star2,
    SUM(CASE WHEN (stars=3) THEN 1 ELSE 0 END) AS star3,
    SUM(CASE WHEN (stars=4) THEN 1 ELSE 0 END) AS star4,
    SUM(CASE WHEN (stars=5) THEN 1 ELSE 0 END) AS star5,
    AVG(stars) AS rate
    '))
                        ->where('property_id', $propertyId)
                        ->groupby('property_id')->first();
        return $results;
    }

}
