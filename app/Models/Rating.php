<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'user_ratings';
    protected $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['from_user', 'to_user', 'remarks', 'rating', 'add_type', 'deal_id', 'advertisement_id'];
}
