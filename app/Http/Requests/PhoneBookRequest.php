<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneBookRequest extends FormRequest
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
            'number' => array('required', 'regex:/((^7|^\+7|^8)(\d{6,16}))|(.?(^\d{3,5}))/'),
            'name'  => 'required|string|min:2|max:255',
        ];
    }
}

// ((^7|^\+7|^8).\d{6,16})|(^\d{3,5})
