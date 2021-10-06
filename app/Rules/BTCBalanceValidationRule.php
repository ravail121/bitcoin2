<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class BTCBalanceValidationRule implements Rule
{
    /**
     * @var App\Models\User
     */
    private $user;

    /**
     * Create a new rule instance.
     *
     * @param App\Models\User $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $cryptoBalance = $this->user->cryptoBalances()->where('gateway_id', 505)->first();

        if (!$cryptoBalance) {
            return;
        }

        return ($cryptoBalance->balance >= $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient balance.';
    }
}
