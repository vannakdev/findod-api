<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class ProjectNameTranslation extends Model {

    
    protected $table = 'project_name_translations';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'locale',
        'project_name_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id','locale', 'created_at', 'updated_at','project_name_id'];

    public function ProjectName() {
        return $this->belongsTo('App\ProjectName');
    }
}
