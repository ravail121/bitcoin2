<?php

namespace App\Http\Requests\Crypto;

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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
        ];
    }

    public function params()
    {
        if ($this->status != null) {
            $status = 1;
        } else {
            $status = 0;
        }

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $status,
        ];

        return $data;
    }
}
