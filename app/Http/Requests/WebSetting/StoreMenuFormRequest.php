<?php

namespace App\Http\Requests\WebSetting;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuFormRequest extends FormRequest
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
        $nameRule = 'required|unique:menus,name';

        if (!is_null($this->route('id'))) {
            $nameRule .= ',' . $this->route('id');
        }

        return [
            'name' => $nameRule,
            'description' => 'required',
        ];
    }
}
