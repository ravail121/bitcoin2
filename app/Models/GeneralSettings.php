<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    public $timestamps = false;

    protected $table = 'basic_settings';

    protected $guarded = ['id'];
}
