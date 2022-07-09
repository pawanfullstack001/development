<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicetype extends Model
{
    //

    protected $table = 'servicetype';


    public function organizationtype(){
        return $this->belongsTo('App\Organizationtype','organization_id','id');
    }

}
