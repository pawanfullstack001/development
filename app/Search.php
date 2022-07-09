<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    //

    protected $table = 'search_map';


    public function organizationtype(){
        return $this->belongsTo('App\Organizationtype','organization_id','id');
    }

}