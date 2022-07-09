<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Organization extends Authenticatable
{

    protected $guard = 'organization';
    
    protected $table = 'organization';

    public function type(){
        return $this->belongsTo('App\Organizationtype','type','id');
    }
    
}
