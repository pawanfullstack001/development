<?php

namespace App\Models;

use App\Appuser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;
    public function driver()
    {
        return $this->belongsTo(Appuser::class,'driver_id','id');
    }
}
