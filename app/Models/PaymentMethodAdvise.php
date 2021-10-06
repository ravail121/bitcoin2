<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodAdvise extends Model
{
    protected $table = 'payment_method_advises';
    protected $fillable = array('username','advice','method_id','status');
}
