<?php

namespace App\Http\Requests\Etemplate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSMSAPIFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'smsapi' => 'required',
        ];
    }
}
