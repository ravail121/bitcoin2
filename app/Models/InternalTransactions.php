<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalTransactions extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'address',
        'amount',
        'fee',
        'status',
        'description'
    ];

    /**
     * Get the user that owns the withdraw request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
