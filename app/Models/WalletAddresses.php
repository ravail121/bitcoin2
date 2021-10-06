<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletAddresses extends Model
{
    protected $table = 'wallet_addresses';
    protected $fillable = ['addresses','status','wallet'];
    public $timestamps = false;
}
