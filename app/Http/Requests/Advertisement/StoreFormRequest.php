<?php

namespace App\Http\Requests\Advertisement;

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
            'crypto_id' => 'required',
            'min_amount' => 'required|numeric|min:1',
            // 'max_amount' => 'required|numeric|min:1',
            'term_detail' => 'required',
            'margin' => 'required|numeric',
            'payment_detail' => 'required',
            'currency_id' => 'required',
            'country_id'=>'required',
            'agree' => 'required',
            'description' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'crypto_id.required' => 'Payment Method is required',
            
        ];
    }
}
