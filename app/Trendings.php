<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trendings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'trending';
    protected $fillable = [
        'id',
        'tre_pro_id',
        'tre_counter',
        'tre_date',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id'];

    public function properties()
    {
        return $this->belongsTo('App\Properties', 'tre_pro_id');
    }

    public static function getTrendingId($number_per_page, $selectColumns)
    {
        $trendings = self::with(
                ['properties' => function ($property) use ($selectColumns) {
                    $property->with('currency', 'residence', 'propertyType', 'amenities')
                        ->select($selectColumns);
                }])
                ->orderBy('tre_date', 'desc')
                ->orderBy('tre_counter', 'desc')
                ->paginate($number_per_page);
        $propertyList = [];

        foreach ($trendings as $trending) {
            if ($trending['properties'] != null) {
                array_push($propertyList, $trending['properties']);
            }
        }

        return $propertyList;
    }

    public static function getTrendingIdWithDistance($number_per_page)
    {
        $trendings = self::with('properties')
                ->orderBy('tre_date', 'desc')
                ->orderBy('tre_counter', 'desc')
                ->paginate($number_per_page);
        $propertyList = [];
        foreach ($trendings as $trending) {
            if ($trending['properties'] != null) {
                $property = $trending['properties'];
                array_push($propertyList, $property->id);
            }
        }

        return $propertyList;
    }

    public static function getTrendingId001($number_per_page, $selectColumns)
    {
        $getPropertyId = self::with(['properties' => function ($query) use ($selectColumns) {
            $query->select($selectColumns);
            $query->with('currency');
        },
                ])
                ->orderBy('tre_date', 'desc')
                ->orderBy('tre_counter', 'desc')
                ->paginate($number_per_page);

        return $getPropertyId;
    }
}
