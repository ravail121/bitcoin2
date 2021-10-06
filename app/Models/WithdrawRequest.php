<?php

namespace App\Models;

use App\Scopes\OrderScope;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    /**
     * Defaine the statuses list.
     *
     * @return array
     */
    public static $statuses = [
        self::STATUS_PENDING,
        self::STATUS_COMPLETED,
        self::STATUS_REJECTED,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'address',
        // 'pre_main_amo',
        'amount',
        'fee',
        'main_amo',
        'status',
        'txn_id',
        'description'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderScope);
    }

    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getIsPendingAttribute()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Get the user that owns the withdraw request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
