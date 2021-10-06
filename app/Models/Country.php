<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function user_login()
    {
        return $this->belongsToMany(UserLogin::class);
    }
}
