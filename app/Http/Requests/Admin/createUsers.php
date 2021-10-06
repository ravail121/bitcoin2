<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class createUsers extends FormRequest
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
            'name' => 'required|max:20|unique:admins,username,'.$this->id,
            'email' => 'required|max:50|unique:admins,email,'.$this->id,
            'mobile' => 'required|unique:admins,mobile,'.$this->id,
            'role' => 'required',
            'image'=> 'mimes:png,jpeg,jpg|max:4096',
        ];
    }
}
