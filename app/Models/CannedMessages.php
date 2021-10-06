<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CannedMessages extends Model
{
    protected $fillable = [
        'user_id',
        'message'
    ];
}
