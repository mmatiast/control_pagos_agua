<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagoStoreRequest extends FormRequest
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
            'fecha_deposito' => 'required',
            'numero_deposito' => 'required',
            'monto_deposito' => 'required',
            'deposito' => 'nullable|max:10000',
            'contador_id' => 'required',
            'banco_id' => 'required',
            'forma_pago_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'fecha_deposito' => 'fecha de depósito',
            'numero_deposito' => 'número de depósito',
            'monto_deposito' => 'monto de depósito',
            'deposito' => 'foto de depósito',
            'contador_id' => 'id del contador',
            'banco_id' => 'banco',
            'forma_pago_id' => 'forma de pago'
        ];
    }
}
