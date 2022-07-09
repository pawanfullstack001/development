<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponLog extends Model
{
    public function coupon():BelongsTo
    {
        return $this->belongsTo(Coupon::class,'coupon_id','id');
    }
}
