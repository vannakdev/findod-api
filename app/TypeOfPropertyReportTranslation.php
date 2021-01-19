<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeOfPropertyReportTranslation extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'property_reporty_type_translations';
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at'];

}
