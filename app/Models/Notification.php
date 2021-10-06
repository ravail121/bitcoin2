<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $table = 'notifications';
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
    public function to_user()
    {
        return $this->hasOne(User::class, 'id', 'to_user')->withDefault();
    }

    public function from_user()
    {
        return $this->hasOne(User::class, 'id', 'from_user')->withDefault();
    }
}
