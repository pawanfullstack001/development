<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Subadmin extends Authenticatable
{
    protected $table = 'subadmin';

    protected $guard = 'subadmin';
}
