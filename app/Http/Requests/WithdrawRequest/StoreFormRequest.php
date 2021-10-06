<?php

namespace App\Http\Requests\WithdrawRequest;

use App\Rules\BTCValidatorRule;
use App\Rules\BTCBalanceValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', new BTCBalanceValidationRule($this->user())],
            'address' => ['required', 'string', new BTCValidatorRule],
        ];
    }
}
