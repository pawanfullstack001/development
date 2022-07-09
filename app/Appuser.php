<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appuser extends Model
{
    //

    protected $table = 'appuser';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];
    public function city(){
        return $this->belongsTo('App\City','city','id');
    }
    public function country(){
        return $this->belongsTo('App\Country','country','id');
    }
    public function servicetype(){
        return $this->belongsTo('App\Servicetype','service_type','id');
    }
    public function organizationtype(){
        return $this->belongsTo('App\Organizationtype','organization_type','id');
    }
    public function vehicletype(){
        return $this->belongsTo('App\Vehicletype','vehicle_type','id');
    }
    public function verified(){
        return $this->belongsTo('App\Subadmin','verified_by','id');
    }
}
