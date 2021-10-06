<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';
    const TYPE_MANUAL = 'manual';

    /**
     * Defaine the types list.
     * @return array
     */
    public static $types = [
        self::TYPE_DEPOSIT,
        self::TYPE_WITHDRAW,
        self::TYPE_MANUAL,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'txid',
        'status',
        // 'pre_main_amo',
        'amount',
        'main_amo',
        'fee',
        'type',
        'address',
        'user_id',
        'confirmations',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'pending',
        'fee' => 0,
    ];

    /**
     * Get the status for the transaction.
     *
     * @return bool
     */
    public function getIsCompleteAttribute()
    {
        return $this->status === 'completed';
    }

    /**
     * Get the type for the transaction.
     *
     * @return bool
     */
    public function getIsDepositeAttribute()
    {
        return $this->type === self::TYPE_DEPOSIT;
    }

    /**
     * Scope a query to only include pending transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include deposit type transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeposits($query)
    {
        return $query->where('type', self::TYPE_DEPOSIT);
    }

    /**
     * Scope a query to only include withdraw type transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithdraws($query)
    {
        return $query->where('type', self::TYPE_WITHDRAW);
    }

    /**
     * Scope a query to only include history transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHistoryBalance($query)
    {
        return $query->where('status', 'add')
            ->orWhere('status', 'substract')
            ->orWhere('status', 'completed');
    }

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
