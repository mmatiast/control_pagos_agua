<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'email' => 'required|unique:users,email',
            'cui' => 'required|unique:users,cui',
            'sexo' => 'required',
            'role_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'role_id' => 'rol'
        ];
    }
}
