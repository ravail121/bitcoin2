<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateNote extends Model
{
    protected $table = 'users_private_note';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'to_user_id',
        'from_user_id',
        'note'
    ];

    /**
     * Get the user that owns the withdraw request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
