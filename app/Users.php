<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Users extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable,
        Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'first_name', 'username', 'last_name', 'gender', 'playerId', 'country_code', 'phone', 'dob', 'photo', 'company_name',
        'status',
        'company_address', 'company_number', 'company_licence', 'setting', 'active', 'follow', 'lat', 'lng',
    ];
    protected $date = ['dob', 'updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'api-token', 'favorite_counter', 'lat', 'lng'];

    public function getCompanyLicenceAttribute($value)
    {
        if (isset($value) and $value != '') {
            return env('APP_URL').'uploads/company_licence/'.$value;
        } else {
            return env('APP_URL').'uploads/company_licence/default.jpg';
        }
    }

    public function getPhotoAttribute($value)
    {
        if (isset($value) and $value != '') {
            return env('APP_URL').'uploads/profile_image/'.$value;
        } else {
            return env('APP_URL').'uploads/profile_image/default.png';
        }
    }

    public function getProviderIdAttribute($value)
    {
        $providerList = ['Facebook', 'Google', 'OD App'];
        if ($value == null):
            return $providerList[2];
        endif;

        return $providerList[$value - 1];
    }

    public function getFollowdAttribute($value)
    {
        return 1000;
    }

    public function getSettingAttribute($value)
    {
        if (isset($value) and $value != ''):
            return json_decode($value);
        endif;
    }

    public function setProviderIdAttribute($value)
    {
        if (isset($value) and $value != ''):
            $this->attributes['provider_id'] = 3; // defualt value for sample user
        endif;
        $this->attributes['provider_id'] = $value;
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setSettingAttribute($value)
    {
        $setSetting = '{"gps":1, "language": "en","location":"11.564959 104.925930", "notification": {"sms": 1, "push": 1, "email": 1}}';
        if (isset($value) and $value != '') {
            $this->attributes['setting'] = $value;
        } else {
            $this->attributes['setting'] = $setSetting;
        }
    }

    /**
     * The users that have favorite a property.
     */
    public function favorites()
    {
        return $this->belongsToMany('App\Properties', 'favorites', 'users_id')->withoutGlobalScopes([\App\Scopes\PropertyScope::class]);
    }

    public function ratings()
    {
        return $this->hasMany('App\Ratings');
    }

    public function properties()
    {
        return $this->hasMany('App\Properties', 'pro_use_id')->withoutGlobalScopes([\App\Scopes\PropertyScope::class]);
    }

    public function role()
    {
        return $this->belongsTo('App\Role', 'userol_id');
    }

    public function isAdmin()
    {
        return $this->userol_id == 1;
    }
}
