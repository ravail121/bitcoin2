<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitCoinPrice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'btc_price'
    ];
}
