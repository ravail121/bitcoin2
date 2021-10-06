<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    protected $table = 'user_logins';

    protected $fillable = ['user_id','user_ip','location','details', 'country_name', 'browser', 'action', 'platform', 'is_country_changed'];
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'location');
    }
}
