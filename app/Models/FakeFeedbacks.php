<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FakeFeedbacks extends Model
{
    protected $table = 'fake_feedbacks';
    protected $fillable = [
        'feedback'
    ];
}
