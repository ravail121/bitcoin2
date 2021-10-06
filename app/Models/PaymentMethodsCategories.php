<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodsCategories extends Model
{
    protected $table = 'payment_method_categories';
    protected $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];


    

}
