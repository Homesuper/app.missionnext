<?php


namespace MissionNext\Models\Coupon;


use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected  $table = 'coupons';

    protected $fillable = ['code', 'is_active', 'value'];

    /**
     * @param $query
     * @param $code
     *
     * @return boolean
     */
    public function scopeDisable($query, $code)
    {
        return static::whereCode($code)->update(['is_active' => false]);
    }
} 