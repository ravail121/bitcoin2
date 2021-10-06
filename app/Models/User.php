<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function deposit()
    {
        return $this->hasMany(Deposit::class, 'user_id');
    }
    public function notification()
    {
        return $this->hasMany(Notification::class, 'to_user');
    }

    /**
     * Get the crypto balances for the user.
     */
    public function cryptoBalances()
    {
        return $this->hasMany(UserCryptoBalance::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the withdraw requests for the user.
     */
    public function withdrawRequests()
    {
        return $this->hasMany(WithdrawRequest::class);
    }

    /**
     * Get the withdraw requests for the user.
     */
    public function sendRequests()
    {
        return $this->hasMany(InternalTransactions::class);
    }

    /**
     * Get the trxes for the user.
     */
    public function trxes()
    {
        return $this->hasMany(Trx::class);
    }

    /**
     * Get the crypto addvertises requests for the user.
     */
    public function cryptoAddvertises()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function getLinkToPageAttribute()
    {
        return '<a href="/profile/' . $this->username . '">' . $this->username . '</a>';
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function login()
    {
        return $this->belongsTo(UserLogin::class, 'user_id');
    }
}
