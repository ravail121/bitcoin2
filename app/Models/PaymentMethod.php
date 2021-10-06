<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $guarded = ['id'];
    protected $with= ['countries'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'status','category_ids','question_one','question_two','answer_one','answer_two'];

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class)->withDefault();
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }
    
}
