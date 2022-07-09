<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicletype extends Model
{
    //

    protected $table = 'vehicletype';

    public function servicetype(){
        return $this->belongsTo('App\Servicetype','service_type','id');
    }

}
