<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $guarded = ['id'];
    protected $table = 'advertisements';
    /**
     * Scope a query to only include opened addvertise's.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpened($query)
    {
        return $query->where('status', 1);
    }

    public function gateway()
    {
        return $this->hasOne(Gateway::class, 'id', 'gateway_id')->withDefault();
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'method_id')->withDefault();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withDefault();
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id')->withDefault();
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id')->withDefault();
    }

    public function advertise()
    {
        return $this->belongsTo(AdvertiseDeal::class)->withDefault();
    }
}
