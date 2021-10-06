<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCryptoBalance extends Model
{
    protected $guarded = ['id'];

    public function crypto()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class)->withDefault();
    }

    /**
     * Get the user that owns the crypto balance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
