<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Murich\PhpCryptocurrencyAddressValidation\Validation\BTC as BTCValidator;

class BTCValidatorRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validator = new BTCValidator($value);

        return true; //$validator->validate();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not valid.';
    }
}
