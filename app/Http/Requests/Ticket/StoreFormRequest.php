<?php

namespace App\Http\Requests\Ticket;

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
            'subject' => 'required',
            'detail' => 'required',
            'replyto' => 'required',
            'files' =>'mimes:pdf,png,jpeg,jpg|max:4096',
            'g-recaptcha-response' => 'required|captcha'
        ];

    }
}
