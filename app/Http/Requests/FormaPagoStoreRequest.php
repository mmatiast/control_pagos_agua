<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormaPagoStoreRequest extends FormRequest
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
            'forma_pago' => 'required|unique:forma_pagos,forma_pago'
        ];
    }

    public function attributes()
    {
        return [
            'forma_pago' => 'forma de pago'
        ];
    }
}
