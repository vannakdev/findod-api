<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;

    protected $dates = [
        'created_at',
        'lockout_at',
  ];
}
