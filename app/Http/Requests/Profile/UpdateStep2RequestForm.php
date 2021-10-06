<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStep2RequestForm extends FormRequest
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
            'id_photo' => 'image|mimes:jpeg,png,jpg|max:4096',
            'id_photo_id' => 'image|mimes:jpeg,png,jpg|max:4096',
            'address_photo' => 'image|mimes:jpeg,png,jpg|max:4096',
        ];
    }
    public function messages()
    {
        return [
            'id_photo.mimes' => 'Copy of ID with a photo must be a file of type: jpeg, png, jpg',
            'id_photo_id.mimes' => 'Proof of address  must be a file of type: jpeg, png, jpg',
            'address_photo.mimes' => 'Photo of yourself holding the ID beside your face  must be a file of type: jpeg, png, jpg',
            'id_photo.image' => 'Copy of ID with a photo must be a image of type: jpeg, png, jpg',
            'id_photo_id.image' => 'Proof of address  must be a image of type: jpeg, png, jpg',
            'address_photo.image' => 'Photo of yourself holding the ID beside your face  must be a image of type: jpeg, png, jpg',
            'id_photo.max' => 'Copy of ID with a photo may not be greater than 4MB',
            'id_photo_id.max' => 'Proof of address  may not be greater than 4MB',
            'address_photo.max' => 'Photo of yourself holding the ID beside your face may not be greater than 4MB',
        ];

    }
}
