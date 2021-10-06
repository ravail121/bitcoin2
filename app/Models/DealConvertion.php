<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealConvertion extends Model
{
    protected $guarded = ['id'];

    public function conversation()
    {
        return $this->belongsTo(AdvertiseDeal::class)->withDefault();
    }
}
